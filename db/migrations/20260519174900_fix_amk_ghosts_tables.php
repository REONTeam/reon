<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FixAmkGhostsTables extends AbstractMigration
{
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

    private function columnRow(string $table, string $column): ?array
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT COLUMN_NAME,
                    COLUMN_TYPE,
                    IS_NULLABLE,
                    COLUMN_DEFAULT,
                    CHARACTER_SET_NAME,
                    COLLATION_NAME,
                    EXTRA,
                    ORDINAL_POSITION
               FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = " . $pdo->quote($table) . "
                AND COLUMN_NAME = " . $pdo->quote($column) . "
              LIMIT 1"
        );
        return $row ?: null;
    }

    private function buildColumnDefinition(array $col): string
    {
        // MySQL requires the full column definition for MODIFY COLUMN, not just the position.
        $def = $col['COLUMN_TYPE']; // e.g., char(1)

        // Preserve character set/collation if present (helps avoid accidental changes).
        if (!empty($col['CHARACTER_SET_NAME'])) {
            $def .= " CHARACTER SET {$col['CHARACTER_SET_NAME']}";
        }
        if (!empty($col['COLLATION_NAME'])) {
            $def .= " COLLATE {$col['COLLATION_NAME']}";
        }

        $def .= (strtoupper((string)$col['IS_NULLABLE']) === 'NO') ? " NOT NULL" : " NULL";

        // Preserve default if present.
        if ($col['COLUMN_DEFAULT'] !== null) {
            $pdo = $this->getAdapter()->getConnection();
            $def .= " DEFAULT " . $pdo->quote((string)$col['COLUMN_DEFAULT']);
        }

        // Preserve extra flags if any (unlikely for game_region, but safe).
        if (!empty($col['EXTRA'])) {
            $def .= " " . $col['EXTRA'];
        }

        return $def;
    }

    private function isImmediatelyAfter(string $table, string $leftCol, string $rightCol): bool
    {
        $left = $this->columnRow($table, $leftCol);
        $right = $this->columnRow($table, $rightCol);
        if (!$left || !$right) return false;
        return ((int)$right['ORDINAL_POSITION'] === (int)$left['ORDINAL_POSITION'] + 1);
    }

    private function moveColumn(
        string $table, string $column, string $after): void
    {
        if (!$this->tableExists($table)) return;

        $col = $this->columnRow($table, $column);
        $aft = $this->columnRow($table, $after);
        if (!$col || !$aft) return;

        if ($this->isImmediatelyAfter($table, $column, $after)) {
            return; // already positioned correctly
        }

        $def = $this->buildColumnDefinition($col);
        $this->execute("ALTER TABLE `{$table}` MODIFY COLUMN `{$column}` {$def} AFTER `{$after}`");
    }

    public function up(): void
    {
        if ($this->tableExists('amk_ghosts')) {
            if ($this->columnExists('amk_ghosts', 'course_no'))
                $this->execute("ALTER TABLE `amk_ghosts` DROP COLUMN `course_no`");
            if ($this->columnExists('amk_ghosts', 'unk18'))
                $this->execute("ALTER TABLE `amk_ghosts` DROP COLUMN `unk18`");
            $this->moveColumn('amk_ghosts', 'course', 'acc_id');
            $this->moveColumn('amk_ghosts', 'driver', 'state');
        }

        if ($this->tableExists('amk_ghosts_mobilegp')) {
            if ($this->columnExists('amk_ghosts_mobilegp', 'course_no'))
                $this->execute("ALTER TABLE `amk_ghosts_mobilegp` DROP COLUMN `course_no`");
            if ($this->columnExists('amk_ghosts_mobilegp', 'unk18'))
                $this->execute("ALTER TABLE `amk_ghosts_mobilegp` DROP COLUMN `unk18`");
            $this->moveColumn('amk_ghosts_mobilegp', 'course', 'acc_id');
            $this->moveColumn('amk_ghosts_mobilegp', 'driver', 'state');
        }
    }

    public function down(): void
    {
        if ($this->tableExists('amk_ghosts')) {
            if (!$this->columnExists('amk_ghosts', 'course_no')) {
                $this->execute("ALTER TABLE `amk_ghosts` ADD COLUMN `course_no` TINYINT(3) UNSIGNED NOT NULL AFTER `player_id`");
                $this->execute("UPDATE TABLE `amk_ghosts` SET `course_no`=`course`");
            }
            if (!$this->columnExists('amk_ghosts', 'unk18')) {
                $this->execute("ALTER TABLE `amk_ghosts` ADD COLUMN `unk18` SMALLINT(5) UNSIGNED NOT NULL AFTER `state`");
            }
            $this->moveColumn('amk_ghosts', 'course', 'unk18');
            $this->moveColumn('amk_ghosts', 'driver', 'course_no');
        }

        if ($this->tableExists('amk_ghosts_mobilegp')) {
            if (!$this->columnExists('amk_ghosts_mobilegp', 'course_no')) {
                $this->execute("ALTER TABLE `amk_ghosts_mobilegp` ADD COLUMN `course_no` TINYINT(3) UNSIGNED NOT NULL AFTER `player_id`");
                $this->execute("UPDATE TABLE `amk_ghosts_mobilegp` SET `course_no`=`course`");
            }
            if (!$this->columnExists('amk_ghosts_mobilegp', 'unk18')) {
                $this->execute("ALTER TABLE `amk_ghosts_mobilegp` ADD COLUMN `unk18` SMALLINT(5) UNSIGNED NOT NULL AFTER `state`");
            }
            $this->moveColumn('amk_ghosts_mobilegp', 'course', 'unk18');
            $this->moveColumn('amk_ghosts_mobilegp', 'driver', 'course_no');
        }
    }
}
