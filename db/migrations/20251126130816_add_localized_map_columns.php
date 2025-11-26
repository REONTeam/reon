<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddLocalizedMapColumns extends AbstractMigration
{
    /**
     * Add region-specific name and category columns to bww_maps table
     *
     * Map data is now stored per-region to support localized names and categories:
     * - map_name_j, map_name_e: Map name in each language
     * - category_j, category_e: Category text in each language (e.g., "オフィシャルマップ" / "Official Map")
     *
     * Map ID ranges:
     * - 0000-1999: Official maps (from original game)
     * - 2000-9999: REON designer maps
     */
    public function change(): void
    {
        $table = $this->table('bww_maps');

        // Add localized name columns
        $table->addColumn('map_name_j', 'string', [
            'limit' => 12,
            'null' => true,
            'after' => 'map_name',
            'comment' => 'Japanese map name (12 bytes max)',
        ]);
        $table->addColumn('map_name_e', 'string', [
            'limit' => 12,
            'null' => true,
            'after' => 'map_name_j',
            'comment' => 'English map name (12 bytes max)',
        ]);

        // Add localized category columns
        $table->addColumn('category_j', 'string', [
            'limit' => 9,
            'null' => true,
            'after' => 'map_name_e',
            'comment' => 'Japanese category text (9 bytes max)',
        ]);
        $table->addColumn('category_e', 'string', [
            'limit' => 9,
            'null' => true,
            'after' => 'category_j',
            'comment' => 'English category text (9 bytes max)',
        ]);

        $table->update();

        // Migrate existing data: copy map_name to map_name_j
        $this->execute("UPDATE bww_maps SET map_name_j = map_name WHERE map_name_j IS NULL");
    }
}
