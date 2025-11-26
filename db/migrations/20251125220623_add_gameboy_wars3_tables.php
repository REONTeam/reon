<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddGameboyWars3Tables extends AbstractMigration
{
    /**
     * Add tables for Game Boy Wars 3 (CGB-BWWJ/CGB-BWWE)
     *
     * Tables:
     * - bww_maps: Downloadable maps (shared across all regions)
     * - bww_messages: Message center content (region-specific)
     * - bww_mercenary_prices: Mercenary unit pricing (region-specific)
     */
    public function change(): void
    {
        // Maps table - shared across all game regions
        // The 2-byte header (0x20 0x00 for J, 0x21 0x00 for E) is prepended dynamically on serve
        if (!$this->hasTable('bww_maps')) {
            $table = $this->table('bww_maps', ['id' => true, 'signed' => false]);
            $table->addColumn('map_id', 'char', ['limit' => 4, 'null' => false, 'comment' => '4-digit map identifier'])
                  ->addColumn('map_name', 'string', ['limit' => 12, 'null' => false, 'comment' => 'Map name (12 bytes max)'])
                  ->addColumn('width', 'integer', ['signed' => false, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'null' => false, 'comment' => 'Map width (20-50)'])
                  ->addColumn('height', 'integer', ['signed' => false, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'null' => false, 'comment' => 'Map height (20-50)'])
                  ->addColumn('price_yen', 'integer', ['signed' => false, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_SMALL, 'null' => false, 'default' => 10, 'comment' => 'Price in yen'])
                  ->addColumn('map_data', 'blob', ['null' => false, 'comment' => 'Binary map file data (without 2-byte header)'])
                  ->addColumn('download_count', 'integer', ['signed' => false, 'null' => false, 'default' => 0])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->addColumn('is_active', 'boolean', ['signed' => false, 'null' => false, 'default' => true])
                  ->addIndex(['map_id'], ['unique' => true, 'name' => 'idx_map_id'])
                  ->addIndex(['is_active'], ['name' => 'idx_active'])
                  ->create();
        }

        // Messages table - region-specific (different languages)
        if (!$this->hasTable('bww_messages')) {
            $table = $this->table('bww_messages', ['id' => true, 'signed' => false]);
            $table->addColumn('game_region', 'char', ['limit' => 1, 'null' => false, 'comment' => 'j=Japanese, e=English'])
                  ->addColumn('mailbox_id', 'integer', ['signed' => false, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'null' => false, 'comment' => 'Mailbox number (0-15)'])
                  ->addColumn('serial_number', 'char', ['limit' => 4, 'null' => false, 'comment' => '4-digit serial version'])
                  ->addColumn('subject', 'string', ['limit' => 255, 'null' => true])
                  ->addColumn('message_data', 'blob', ['null' => false, 'comment' => 'Binary message data (Shift JIS with CRLF)'])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->addColumn('is_active', 'boolean', ['signed' => false, 'null' => false, 'default' => true])
                  ->addIndex(['game_region', 'mailbox_id'], ['unique' => true, 'name' => 'idx_region_mailbox'])
                  ->addIndex(['is_active'], ['name' => 'idx_msg_active'])
                  ->create();
        }
    }
}
