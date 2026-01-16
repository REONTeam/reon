<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Ensure bxt_news.is_custom appears to the left of bxt_news.game_region.
 *
 * This is a non-functional schema ordering fix only. It is safe to run even if:
 * - bxt_news lacks either column (no-op)
 * - the column order is already correct (no-op)
 */
final class ReorderBxtNewsIsCustomBeforeGameRegion extends AbstractMigration
{
    public function up() {
        // Only meaningful on MySQL/MariaDB.
        $db = $this->getAdapter();

        // Ensure column exists (migration step1 adds it).
        $table = $this->table('bxt_news');
        if (!$table->hasColumn('is_custom') || !$table->hasColumn('game_region')) {
            return;
        }

        // Introspect current column ordering.
        $rows = $db->fetchAll(
            "SELECT COLUMN_NAME AS name
".
            "  FROM INFORMATION_SCHEMA.COLUMNS
".
            " WHERE TABLE_SCHEMA = DATABASE()
".
            "   AND TABLE_NAME = 'bxt_news'
".
            " ORDER BY ORDINAL_POSITION ASC"
        );

        $names = array_map(function($r) { return $r['name']; }, $rows);

        $posIs     = array_search('is_custom', $names, true);
        $posRegion = array_search('game_region', $names, true);

        if ($posIs === false || $posRegion === false) {
            return;
        }

        // Already placed before game_region.
        if ($posIs < $posRegion) {
            return;
        }

        // If game_region is first, move is_custom to FIRST.
        if ($posRegion === 0) {
            $this->execute(
                "ALTER TABLE `bxt_news` MODIFY COLUMN `is_custom` TINYINT(1) NOT NULL DEFAULT 0 FIRST;"
            );
            return;
        }

        // Otherwise, move is_custom to immediately before game_region by placing it AFTER
        // the column that currently precedes game_region.
        $prevCol = $names[$posRegion - 1];

        // Defensive: only allow normal identifier characters.
        if (!preg_match('/^[A-Za-z0-9_]+$/', $prevCol)) {
            return;
        }

        $this->execute(
            "ALTER TABLE `bxt_news` MODIFY COLUMN `is_custom` TINYINT(1) NOT NULL DEFAULT 0 AFTER `{$prevCol}`;"
        );
    }

    public function down() {
        // No-op: ordering-only.
    }
}
