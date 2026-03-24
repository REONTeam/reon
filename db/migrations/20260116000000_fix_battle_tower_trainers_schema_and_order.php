<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FixBattleTowerTrainersSchemaAndOrder extends AbstractMigration
{
    private function tableExists(string $table): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1 AS ok
             FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
             LIMIT 1"
        );

        return (bool) $row;
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

        return (bool) $row;
    }

    /**
     * @return list<string>
     */
    private function currentColumnOrder(string $table): array
    {
        $pdo = $this->getAdapter()->getConnection();
        $rows = $this->fetchAll(
            "SELECT COLUMN_NAME
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
             ORDER BY ORDINAL_POSITION"
        );

        return array_map(static fn(array $row): string => (string) $row['COLUMN_NAME'], $rows);
    }

    private function q(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    private function selectExpr(string $table, string $column, string $fallbackSql): string
    {
        if ($this->columnExists($table, $column)) {
            return $this->q($column);
        }

        return $fallbackSql;
    }

    public function up(): void
    {
        $table = 'bxt_battle_tower_trainers';
        if (!$this->tableExists($table)) {
            return;
        }

        $desiredOrder = [
            'id',
            'game_region',
            'trainer_id',
            'secret_id',
            'no',
            'room',
            'level',
            'level_decode',
            'player_name',
            'player_name_decode',
            'class',
            'class_decode',
            'pokemon1',
            'pokemon1_decode',
            'pokemon2',
            'pokemon2_decode',
            'pokemon3',
            'pokemon3_decode',
            'message_start',
            'message_start_decode',
            'message_win',
            'message_win_decode',
            'message_lose',
            'message_lose_decode',
            'account_id',
            'timestamp',
        ];

        $currentOrder = $this->currentColumnOrder($table);
        $missingCritical = [
            'trainer_id', 'secret_id', 'no', 'room', 'level', 'player_name',
            'class', 'pokemon1', 'pokemon2', 'pokemon3',
            'message_start', 'message_win', 'message_lose',
        ];

        $needsRebuild = ($currentOrder !== $desiredOrder);
        foreach ($missingCritical as $col) {
            if (!$this->columnExists($table, $col)) {
                $needsRebuild = true;
                break;
            }
        }

        if (!$needsRebuild) {
            return;
        }

        $tmp = 'bxt_battle_tower_trainers_fix_20260116';
        if ($this->tableExists($tmp)) {
            $this->execute('DROP TABLE ' . $this->q($tmp));
        }

        $this->execute(
            'CREATE TABLE ' . $this->q($tmp) . " (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `game_region` char(1) DEFAULT NULL,
                `trainer_id` int(5) unsigned DEFAULT NULL COMMENT 'Trainer ID',
                `secret_id` int(5) unsigned DEFAULT NULL COMMENT 'Secret ID',
                `no` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `room` int(10) unsigned DEFAULT NULL,
                `level` tinyint(3) unsigned DEFAULT NULL,
                `level_decode` varchar(16) DEFAULT NULL,
                `player_name` binary(7) DEFAULT NULL COMMENT 'Name of trainer',
                `player_name_decode` varchar(32) DEFAULT NULL,
                `class` int(3) unsigned DEFAULT NULL COMMENT 'Class of trainer',
                `class_decode` varchar(32) DEFAULT NULL,
                `pokemon1` binary(65) DEFAULT NULL COMMENT 'Pokemon',
                `pokemon1_decode` text DEFAULT NULL,
                `pokemon2` binary(65) DEFAULT NULL COMMENT 'Pokemon',
                `pokemon2_decode` text DEFAULT NULL,
                `pokemon3` binary(65) DEFAULT NULL COMMENT 'Pokemon',
                `pokemon3_decode` text DEFAULT NULL,
                `message_start` binary(12) DEFAULT NULL,
                `message_start_decode` text DEFAULT NULL,
                `message_win` binary(12) DEFAULT NULL,
                `message_win_decode` text DEFAULT NULL,
                `message_lose` binary(12) DEFAULT NULL,
                `message_lose_decode` text DEFAULT NULL,
                `account_id` int(10) unsigned DEFAULT NULL,
                `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $insertSql = sprintf(
            'INSERT INTO %s (
                `id`, `game_region`, `trainer_id`, `secret_id`, `no`, `room`, `level`, `level_decode`,
                `player_name`, `player_name_decode`, `class`, `class_decode`,
                `pokemon1`, `pokemon1_decode`, `pokemon2`, `pokemon2_decode`, `pokemon3`, `pokemon3_decode`,
                `message_start`, `message_start_decode`, `message_win`, `message_win_decode`,
                `message_lose`, `message_lose_decode`, `account_id`, `timestamp`
            )
            SELECT
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s
            FROM %s',
            $this->q($tmp),
            $this->selectExpr($table, 'id', 'NULL'),
            $this->selectExpr($table, 'game_region', 'NULL'),
            $this->selectExpr($table, 'trainer_id', 'NULL'),
            $this->selectExpr($table, 'secret_id', 'NULL'),
            $this->selectExpr($table, 'no', '0'),
            $this->selectExpr($table, 'room', 'NULL'),
            $this->selectExpr($table, 'level', 'NULL'),
            $this->selectExpr($table, 'level_decode', 'NULL'),
            $this->selectExpr($table, 'player_name', 'NULL'),
            $this->selectExpr($table, 'player_name_decode', 'NULL'),
            $this->selectExpr($table, 'class', 'NULL'),
            $this->selectExpr($table, 'class_decode', 'NULL'),
            $this->selectExpr($table, 'pokemon1', 'NULL'),
            $this->selectExpr($table, 'pokemon1_decode', 'NULL'),
            $this->selectExpr($table, 'pokemon2', 'NULL'),
            $this->selectExpr($table, 'pokemon2_decode', 'NULL'),
            $this->selectExpr($table, 'pokemon3', 'NULL'),
            $this->selectExpr($table, 'pokemon3_decode', 'NULL'),
            $this->selectExpr($table, 'message_start', 'NULL'),
            $this->selectExpr($table, 'message_start_decode', 'NULL'),
            $this->selectExpr($table, 'message_win', 'NULL'),
            $this->selectExpr($table, 'message_win_decode', 'NULL'),
            $this->selectExpr($table, 'message_lose', 'NULL'),
            $this->selectExpr($table, 'message_lose_decode', 'NULL'),
            $this->selectExpr($table, 'account_id', 'NULL'),
            $this->selectExpr($table, 'timestamp', 'CURRENT_TIMESTAMP'),
            $this->q($table)
        );

        $this->execute($insertSql);
        $this->execute('DROP TABLE ' . $this->q($table));
        $this->execute('RENAME TABLE ' . $this->q($tmp) . ' TO ' . $this->q($table));
    }

    public function down(): void
    {
        // Intentionally no-op.
    }
}
