<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddGameTables extends AbstractMigration
{
    /**
     * Add tables for Zen Nihon GT Senshuken (AGTJ), Exciting Bass (AMGJ), and Yu-Gi-Oh! (AY5J)
     * Only creates tables if they don't already exist
     */
    public function change(): void
    {
        // Zen Nihon GT Senshuken (AGTJ) - Ghost data table
        if (!$this->hasTable('agtj_ghosts')) {
            $table = $this->table('agtj_ghosts', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
                  ->addColumn('course', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('weather', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('car', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('trans', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('gear', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('steer', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('brake', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('tire', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('aero', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('excrs', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('handicap', 'integer', ['signed' => false, 'limit' => 5, 'null' => false])
                  ->addColumn('name', 'binary', ['limit' => 22, 'null' => false])
                  ->addColumn('time', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
                  ->addColumn('date', 'datetime', ['null' => false])
                  ->addColumn('input_data', 'blob', ['limit' => 12124, 'null' => true])
                  ->addColumn('dl_ok', 'datetime', ['null' => true])
                  ->create();
        }

        // Exciting Bass (AMGJ) - Rankings table
        if (!$this->hasTable('amgj_rankings')) {
            $table = $this->table('amgj_rankings', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('ident', 'text', ['limit' => 46, 'null' => false])
                  ->addColumn('name', 'binary', ['limit' => 16, 'null' => false])
                  ->addColumn('blood', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('gender', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('age', 'integer', ['signed' => false, 'limit' => 3, 'null' => false])
                  ->addColumn('weight', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
                  ->create();
        }

        // Yu-Gi-Oh! Duel Monsters 5 Expert I (AY5J) - Rankings table
        if (!$this->hasTable('ay5j_rankings')) {
            $table = $this->table('ay5j_rankings', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('ident', 'text', ['limit' => 64, 'null' => false])
                  ->addColumn('name', 'binary', ['limit' => 32, 'null' => false])
                  ->addColumn('phone_no', 'binary', ['limit' => 20, 'null' => false])
                  ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
                  ->create();
        }
    }
}
