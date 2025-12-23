<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Add `level_decode` immediately to the right of `level` in `bxt_battle_tower_room_leaders`.
 *
 * Compatible with Phinx 0.16.x (no hasColumn()).
 */
final class AddLevelDecodeToBxtBattleTowerRoomLeaders extends AbstractMigration
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

    public function up(): void
    {
        if (!$this->tableExists('bxt_battle_tower_room_leaders')) {
            return;
        }
        if ($this->columnExists('bxt_battle_tower_room_leaders', 'level_decode')) {
            return;
        }

        $this->table('bxt_battle_tower_room_leaders')
            ->addColumn('level_decode', 'string', [
                'limit' => 16,
                'null' => true,
                'default' => null,
                'after' => 'level',
            ])
            ->update();
    }

    public function down(): void
    {
        if (!$this->tableExists('bxt_battle_tower_room_leaders')) {
            return;
        }
        if (!$this->columnExists('bxt_battle_tower_room_leaders', 'level_decode')) {
            return;
        }

        $this->table('bxt_battle_tower_room_leaders')
            ->removeColumn('level_decode')
            ->update();
    }
}
