<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UnifyGameTablesAddGameRegion extends AbstractMigration
{
    private function regionFromLegacy(string $legacyTable): string
    {
        // Legacy convention: region letter is the last alphabetic character of the game-id prefix.
        // Example: "amgj_rankings" -> "j", "ay5j_rankings" -> "j", "agtj_ghosts" -> "j".
        $prefix = explode('_', $legacyTable)[0];
        $last = substr($prefix, -1);
        if (!ctype_alpha($last)) {
            throw new RuntimeException("Cannot infer game region from legacy table name: {$legacyTable}");
        }
        return strtolower($last);
    }

    private function tableExists(string $table): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1
               FROM INFORMATION_SCHEMA.TABLES
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = " . $pdo->quote($table) . "
              LIMIT 1"
        );
        return (bool)$row;
    }

    private function columnExists(string $table, string $column): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1
               FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = " . $pdo->quote($table) . "
                AND COLUMN_NAME = " . $pdo->quote($column) . "
              LIMIT 1"
        );
        return (bool)$row;
    }

    /**
     * MySQL supports only FIRST / AFTER for column placement (no BEFORE).
     * This computes a placement clause that puts the new column before $targetColumn.
     */
    private function beforeColumnClause(string $table, string $targetColumn): string
    {
        $pdo = $this->getAdapter()->getConnection();

        $target = $this->fetchRow(
            "SELECT ORDINAL_POSITION AS pos
               FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = " . $pdo->quote($table) . "
                AND COLUMN_NAME = " . $pdo->quote($targetColumn) . "
              LIMIT 1"
        );

        if (!$target) {
            // If target doesn't exist, safest is to put the column at the front.
            return "FIRST";
        }

        $pos = (int)$target['pos'];
        if ($pos <= 1) {
            return "FIRST";
        }

        $prev = $this->fetchRow(
            "SELECT COLUMN_NAME AS name
               FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = " . $pdo->quote($table) . "
                AND ORDINAL_POSITION = " . (int)($pos - 1) . "
              LIMIT 1"
        );

        if (!$prev || !isset($prev['name'])) {
            return "FIRST";
        }

        $prevName = str_replace('`', '``', (string)$prev['name']);
        return "AFTER `{$prevName}`";
    }

    public function up(): void
    {
        // legacy => [new, placementSpec]
        // placementSpec either:
        //   - string: "AFTER `id`" or "FIRST"
        //   - array:  ["before", "player_id"] meaning place before that column
        $tables = [
            'agtj_ghosts'            => ['agt_ghosts',            'AFTER `id`'],
            'amcj_trades'            => ['amc_trades',            'AFTER `id`'],
            'amgj_rankings'          => ['amg_rankings',          'AFTER `id`'],
            'amkj_ghosts'            => ['amk_ghosts',            'AFTER `id`'],
            'amkj_ghosts_mobilegp'   => ['amk_ghosts_mobilegp',   'AFTER `id`'],
            'amkj_indextime'         => ['amk_indextime',         'AFTER `id`'],
            'amkj_rule'              => ['amk_rule',              'AFTER `id`'],
            'amkj_user_map'          => ['amk_user_map',          ['before', 'player_id']], // left of player_id
            'amoj_ranking'           => ['amo_ranking',           'FIRST'], // left of id
            'ay5j_rankings'          => ['ay5_rankings',          'FIRST'], // left of id
        ];

        foreach ($tables as $legacy => $cfg) {
            $new = $cfg[0];
            $placementSpec = $cfg[1];

            // Rename first (legacy -> new).
            if ($this->tableExists($legacy) && !$this->tableExists($new)) {
                $this->execute("RENAME TABLE `{$legacy}` TO `{$new}`");
            }

            if (!$this->tableExists($new)) {
                continue;
            }

            // Resolve placement clause.
            $posClause = '';
            if (is_array($placementSpec) && count($placementSpec) === 2 && $placementSpec[0] === 'before') {
                $posClause = $this->beforeColumnClause($new, (string)$placementSpec[1]);
            } else {
                $posClause = (string)$placementSpec;
            }

            // Add the column as nullable first, backfill, then enforce NOT NULL.
            if (!$this->columnExists($new, 'game_region')) {
                $this->execute("ALTER TABLE `{$new}` ADD COLUMN `game_region` CHAR(1) NULL {$posClause}");
            }

            // Infer region from the legacy table-name string (not table existence).
            $region = $this->regionFromLegacy($legacy);
            $pdo = $this->getAdapter()->getConnection();
            $this->execute(
                "UPDATE `{$new}` SET `game_region` = " . $pdo->quote($region) . " WHERE `game_region` IS NULL"
            );

            $row = $this->fetchRow("SELECT COUNT(*) AS c FROM `{$new}` WHERE `game_region` IS NULL");
            if ((int)$row['c'] !== 0) {
                throw new RuntimeException("game_region backfill incomplete for {$new}");
            }

            $this->execute("ALTER TABLE `{$new}` MODIFY COLUMN `game_region` CHAR(1) NOT NULL");
        }
    }

    public function down(): void
    {
        // new => legacy
        $tables = [
            'agt_ghosts'            => 'agtj_ghosts',
            'amc_trades'            => 'amcj_trades',
            'amg_rankings'          => 'amgj_rankings',
            'amk_ghosts'            => 'amkj_ghosts',
            'amk_ghosts_mobilegp'   => 'amkj_ghosts_mobilegp',
            'amk_indextime'         => 'amkj_indextime',
            'amk_rule'              => 'amkj_rule',
            'amk_user_map'          => 'amkj_user_map',
            'amo_ranking'           => 'amoj_ranking',
            'ay5_rankings'          => 'ay5j_rankings',
        ];

        foreach ($tables as $new => $legacy) {
            if (!$this->tableExists($new)) {
                continue;
            }

            if ($this->columnExists($new, 'game_region')) {
                $this->execute("ALTER TABLE `{$new}` DROP COLUMN `game_region`");
            }

            if (!$this->tableExists($legacy)) {
                $this->execute("RENAME TABLE `{$new}` TO `{$legacy}`");
            }
        }
    }
}
