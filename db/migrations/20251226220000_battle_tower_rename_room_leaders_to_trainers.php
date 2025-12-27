<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Rename:
 *   bxt_battle_tower_room_leaders -> bxt_battle_tower_trainers
 *
 * Idempotent: if already renamed, does nothing.
 */
final class BattleTowerRenameRoomLeadersToTrainers extends AbstractMigration
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

    public function up(): void
    {
        $old = 'bxt_battle_tower_room_leaders';
        $new = 'bxt_battle_tower_trainers';

        if ($this->tableExists($new)) {
            return; // already renamed
        }
        if (!$this->tableExists($old)) {
            return; // nothing to rename
        }

        $this->table($old)->rename($new)->save();
    }

    public function down(): void
    {
        $old = 'bxt_battle_tower_room_leaders';
        $new = 'bxt_battle_tower_trainers';

        if ($this->tableExists($old)) {
            return; // already rolled back
        }
        if (!$this->tableExists($new)) {
            return; // nothing to roll back
        }

        $this->table($new)->rename($old)->save();
    }
}
