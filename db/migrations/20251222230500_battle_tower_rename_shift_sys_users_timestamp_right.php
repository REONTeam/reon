<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Phinx 0.16.x does NOT provide AbstractMigration::hasColumn().
 * This migration uses INFORMATION_SCHEMA checks instead, and is written to be
 * idempotent so it can be re-run safely after a partial failure.
 */
final class BattleTowerRenameShiftSysUsersTimestampRight extends AbstractMigration
{
    private function tableExists(string $table): bool
    {
        $row = $this->fetchRow(
            "SELECT 1 AS ok
             FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $this->getAdapter()->getConnection()->quote($table) . "
             LIMIT 1"
        );
        return (bool)$row;
    }

    private function columnExists(string $table, string $column): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1 AS ok
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME = " . $pdo->quote($column) . "
             LIMIT 1"
        );
        return (bool)$row;
    }

    private function getColumnOrdinal(string $table, string $column): ?int
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT ORDINAL_POSITION
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME = " . $pdo->quote($column) . "
             LIMIT 1"
        );
        if (!$row || !isset($row['ORDINAL_POSITION'])) {
            return null;
        }
        return (int)$row['ORDINAL_POSITION'];
    }

    private function getMaxOrdinal(string $table): ?int
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT MAX(ORDINAL_POSITION) AS max_pos
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
             LIMIT 1"
        );
        if (!$row || $row['max_pos'] === null) {
            return null;
        }
        return (int)$row['max_pos'];
    }

    private function getLastColumnExcept(string $table, string $exceptColumn): ?string
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT COLUMN_NAME
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME <> " . $pdo->quote($exceptColumn) . "
             ORDER BY ORDINAL_POSITION DESC
             LIMIT 1"
        );

        if (!$row || empty($row['COLUMN_NAME'])) {
            return null;
        }
        return (string)$row['COLUMN_NAME'];
    }

    /**
     * Build ALTER TABLE ... MODIFY COLUMN ... AFTER ... preserving existing definition.
     */
    private function buildModifyColumnSql(string $table, string $column, string $afterColumn): ?string
    {
        $afterColumn = trim($afterColumn);
        if ($afterColumn === '') {
            return null;
        }

        $pdo = $this->getAdapter()->getConnection();

        $row = $this->fetchRow(
            "SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, CHARACTER_SET_NAME, COLLATION_NAME
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME = " . $pdo->quote($column) . "
             LIMIT 1"
        );

        if (!$row) {
            return null;
        }

        $type = (string)$row['COLUMN_TYPE'];
        $nullSql = (strtoupper((string)$row['IS_NULLABLE']) === 'YES') ? 'NULL' : 'NOT NULL';

        $defaultSql = '';
        if (array_key_exists('COLUMN_DEFAULT', $row) && $row['COLUMN_DEFAULT'] !== null) {
            $d = (string)$row['COLUMN_DEFAULT'];

            // CURRENT_TIMESTAMP / CURRENT_TIMESTAMP() should stay unquoted.
            if (preg_match('/^CURRENT_TIMESTAMP(?:\(\))?$/i', $d)) {
                $defaultSql = ' DEFAULT CURRENT_TIMESTAMP';
            } else {
                // If it's numeric, keep unquoted; otherwise quote.
                if (preg_match('/^-?\d+(?:\.\d+)?$/', $d)) {
                    $defaultSql = ' DEFAULT ' . $d;
                } else {
                    $defaultSql = ' DEFAULT ' . $pdo->quote($d);
                }
            }
        }

        $extra = strtolower((string)$row['EXTRA']);
        $extraSqlParts = [];
        if (strpos($extra, 'auto_increment') !== false) {
            $extraSqlParts[] = 'AUTO_INCREMENT';
        }
        if (strpos($extra, 'on update current_timestamp') !== false) {
            $extraSqlParts[] = 'ON UPDATE CURRENT_TIMESTAMP';
        }
        $extraSql = $extraSqlParts ? (' ' . implode(' ', $extraSqlParts)) : '';

        // Preserve charset/collation for character columns when present.
        $charset = $row['CHARACTER_SET_NAME'];
        $collation = $row['COLLATION_NAME'];
        $charsetSql = '';
        if ($charset !== null && $collation !== null) {
            $charsetSql = ' CHARACTER SET ' . $charset . ' COLLATE ' . $collation;
        }

        return sprintf(
            "ALTER TABLE `%s` MODIFY COLUMN `%s` %s%s %s%s%s AFTER `%s`",
            $table,
            $column,
            $type,
            $charsetSql,
            $nullSql,
            $defaultSql,
            $extraSql,
            $afterColumn
        );
    }

    private function moveSysUsersTimestampToFarRight(): void
    {
        if (!$this->tableExists('sys_users') || !$this->columnExists('sys_users', 'timestamp')) {
            return;
        }

        // If already last, do nothing.
        $tsPos = $this->getColumnOrdinal('sys_users', 'timestamp');
        $maxPos = $this->getMaxOrdinal('sys_users');
        if ($tsPos !== null && $maxPos !== null && $tsPos >= $maxPos) {
            return;
        }

        $after = $this->getLastColumnExcept('sys_users', 'timestamp');
        if ($after === null || $after === '') {
            return;
        }

        $sql = $this->buildModifyColumnSql('sys_users', 'timestamp', $after);
        if ($sql !== null) {
            $this->execute($sql);
        }
    }

    private function rollbackSysUsersTimestampPosition(): void
    {
        // Best-effort rollback: put trade_region_allowlist after timestamp,
        // which places timestamp immediately before it (typical original layout).
        if (!$this->tableExists('sys_users')) {
            return;
        }
        if (!$this->columnExists('sys_users', 'timestamp') || !$this->columnExists('sys_users', 'trade_region_allowlist')) {
            return;
        }

        $sql = $this->buildModifyColumnSql('sys_users', 'trade_region_allowlist', 'timestamp');
        if ($sql !== null) {
            $this->execute($sql);
        }
    }

    public function up(): void
    {
        // 1) Rename battle tower tables (idempotent)
        if ($this->tableExists('bxt_battle_tower_leaders') && !$this->tableExists('bxt_battle_tower_honor_roll')) {
            $this->table('bxt_battle_tower_leaders')
                ->rename('bxt_battle_tower_honor_roll')
                ->update();
        }

        if ($this->tableExists('bxt_battle_tower_trainers') && !$this->tableExists('bxt_battle_tower_room_leaders')) {
            $this->table('bxt_battle_tower_trainers')
                ->rename('bxt_battle_tower_room_leaders')
                ->update();
        }

        // 2) Add columns to honor roll (formerly bxt_battle_tower_leaders)
        if ($this->tableExists('bxt_battle_tower_honor_roll')) {
            $t = $this->table('bxt_battle_tower_honor_roll');

            if (!$this->columnExists('bxt_battle_tower_honor_roll', 'class')) {
                $t->addColumn('class', 'integer', [
                    'null' => true,
                    'default' => null,
                    'signed' => false,
                    'after' => 'player_name_decode',
                ]);
            }
            if (!$this->columnExists('bxt_battle_tower_honor_roll', 'class_decode')) {
                $t->addColumn('class_decode', 'string', [
                    'limit' => 50,
                    'null' => true,
                    'default' => null,
                    'after' => 'class',
                ]);
            }

            $t->update();
        }

        // 3) Move sys_users.timestamp to the far right
        $this->moveSysUsersTimestampToFarRight();
    }

    public function down(): void
    {
        // 1) Remove added columns (if present)
        if ($this->tableExists('bxt_battle_tower_honor_roll')) {
            $t = $this->table('bxt_battle_tower_honor_roll');

            if ($this->columnExists('bxt_battle_tower_honor_roll', 'class_decode')) {
                $t->removeColumn('class_decode');
            }
            if ($this->columnExists('bxt_battle_tower_honor_roll', 'class')) {
                $t->removeColumn('class');
            }

            $t->update();
        }

        // 2) Rename tables back (idempotent)
        if ($this->tableExists('bxt_battle_tower_honor_roll') && !$this->tableExists('bxt_battle_tower_leaders')) {
            $this->table('bxt_battle_tower_honor_roll')
                ->rename('bxt_battle_tower_leaders')
                ->update();
        }

        if ($this->tableExists('bxt_battle_tower_room_leaders') && !$this->tableExists('bxt_battle_tower_trainers')) {
            $this->table('bxt_battle_tower_room_leaders')
                ->rename('bxt_battle_tower_trainers')
                ->update();
        }

        // 3) Best-effort rollback for sys_users column order
        $this->rollbackSysUsersTimestampPosition();
    }
}
