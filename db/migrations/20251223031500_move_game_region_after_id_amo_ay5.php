<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MoveGameRegionAfterIdAmoAy5 extends AbstractMigration
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

    private function moveGameRegionAfterId(string $table): void
    {
        if (!$this->tableExists($table)) return;

        $id = $this->columnRow($table, 'id');
        $gr = $this->columnRow($table, 'game_region');
        if (!$id || !$gr) return;

        if ($this->isImmediatelyAfter($table, 'id', 'game_region')) {
            return; // already positioned correctly
        }

        $def = $this->buildColumnDefinition($gr);
        $this->execute("ALTER TABLE `{$table}` MODIFY COLUMN `game_region` {$def} AFTER `id`");
    }

    private function moveGameRegionBeforeId(string $table): void
    {
        if (!$this->tableExists($table)) return;

        $id = $this->columnRow($table, 'id');
        $gr = $this->columnRow($table, 'game_region');
        if (!$id || !$gr) return;

        // If game_region is already first, treat as "before id" satisfied.
        if ((int)$gr['ORDINAL_POSITION'] === 1) {
            return;
        }

        $def = $this->buildColumnDefinition($gr);
        $this->execute("ALTER TABLE `{$table}` MODIFY COLUMN `game_region` {$def} FIRST");
    }

    public function up(): void
    {
        $this->moveGameRegionAfterId('amo_ranking');
        $this->moveGameRegionAfterId('ay5_rankings');
    }

    public function down(): void
    {
        // Revert to "left of id" (FIRST) which matches the earlier requirement.
        $this->moveGameRegionBeforeId('amo_ranking');
        $this->moveGameRegionBeforeId('ay5_rankings');
    }
}
