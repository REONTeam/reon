<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Initial database schema migration
 *
 * This migration represents the baseline database schema from commit 0d308b92127df05420a1417eb7c635ead8348b9a
 * This is the schema currently deployed on the production server.
 */
final class InitialSchema extends AbstractMigration
{
    /**
     * Create all tables for the REON database
     */
    public function change(): void
    {
        // System tables
        $this->createSystemTables();

        // Pokemon Crystal (BXT*)
        $this->createBxtjTables(); // Japanese
        $this->createBxteTables(); // English (North America)
        $this->createBxtpTables(); // English (Europe)
        $this->createBxtuTables(); // English (Australia)
        $this->createBxtdTables(); // German
        $this->createBxtfTables(); // French
        $this->createBxtiTables(); // Italian
        $this->createBxtsTables(); // Spanish

        // Pokemon Crystal general tables
        $this->createBxtGeneralTables();

        // Mario Kart Advance (AMKJ)
        $this->createAmkjTables();

        // EX Monopoly (AMOJ)
        $this->createAmojTables();
    }

    private function createSystemTables(): void
    {
        // sys_users
        $table = $this->table('sys_users', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('email', 'string', ['limit' => 254])
              ->addColumn('password', 'string', ['limit' => 255])
              ->addColumn('dion_ppp_id', 'string', ['limit' => 10])
              ->addColumn('dion_email_local', 'string', ['limit' => 8])
              ->addColumn('log_in_password', 'string', ['limit' => 8])
              ->addColumn('money_spent', 'integer', ['limit' => 11])
              ->create();

        // sys_email_change
        $table = $this->table('sys_email_change', ['id' => false, 'primary_key' => 'user_id']);
        $table->addColumn('user_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('new_email', 'string', ['limit' => 254])
              ->addColumn('secret', 'string', ['limit' => 48])
              ->addColumn('time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // sys_password_reset
        $table = $this->table('sys_password_reset', ['id' => false, 'primary_key' => 'user_id']);
        $table->addColumn('user_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('secret', 'string', ['limit' => 48])
              ->addColumn('time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // sys_signup
        $table = $this->table('sys_signup', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('email', 'string', ['limit' => 254])
              ->addColumn('secret', 'string', ['limit' => 48])
              ->addColumn('time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // sys_inbox
        $table = $this->table('sys_inbox', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('sender', 'string', ['limit' => 254])
              ->addColumn('recipient', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('date', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('message', 'blob')
              ->create();
    }

    private function createBxtjTables(): void
    {
        // bxtj_exchange
        $table = $this->table('bxtj_exchange', ['id' => false]);
        $table->addColumn('entry_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'Current time at entry.'])
              ->addColumn('email', 'string', ['limit' => 30, 'comment' => 'DION email'])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
              ->addColumn('offer_gender', 'integer', ['signed' => false, 'limit' => 1, 'comment' => 'Gender of Pokémon'])
              ->addColumn('offer_species', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Decimal Pokémon ID.'])
              ->addColumn('request_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('request_species', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('trainer_name', 'binary', ['limit' => 5, 'comment' => 'Name of player'])
              ->addColumn('pokemon', 'binary', ['limit' => 58, 'comment' => 'Pokémon'])
              ->addColumn('mail', 'binary', ['limit' => 42, 'comment' => 'Held mail of Pokémon'])
              ->addIndex(['account_id', 'trainer_id', 'secret_id'], ['unique' => true, 'name' => 'UNIQUE'])
              ->create();

        // bxtj_battle_tower_records
        $table = $this->table('bxtj_battle_tower_records', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 10])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('email', 'string', ['limit' => 30])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('name', 'binary', ['limit' => 5])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 54])
              ->addColumn('pokemon2', 'binary', ['limit' => 54])
              ->addColumn('pokemon3', 'binary', ['limit' => 54])
              ->addColumn('message_start', 'binary', ['limit' => 12])
              ->addColumn('message_win', 'binary', ['limit' => 12])
              ->addColumn('message_lose', 'binary', ['limit' => 12])
              ->addColumn('num_trainers_defeated', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('num_turns_required', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('damage_taken', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('num_fainted_pokemon', 'integer', ['signed' => false, 'limit' => 3])
              ->create();

        // bxtj_battle_tower_trainers
        $table = $this->table('bxtj_battle_tower_trainers', ['id' => false, 'primary_key' => ['no', 'room', 'level']]);
        $table->addColumn('no', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('name', 'binary', ['limit' => 5])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 54])
              ->addColumn('pokemon2', 'binary', ['limit' => 54])
              ->addColumn('pokemon3', 'binary', ['limit' => 54])
              ->addColumn('message_start', 'binary', ['limit' => 12])
              ->addColumn('message_win', 'binary', ['limit' => 12])
              ->addColumn('message_lose', 'binary', ['limit' => 12])
              ->create();

        // bxtj_battle_tower_leaders
        $table = $this->table('bxtj_battle_tower_leaders', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('name', 'binary', ['limit' => 5])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->create();

        // bxtj_ranking
        $table = $this->table('bxtj_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 5])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'integer', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 12])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxteTables(): void
    {
        // bxte_battle_tower_records
        $table = $this->table('bxte_battle_tower_records', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 10])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('email', 'string', ['limit' => 30])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('name', 'binary', ['limit' => 7])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 59])
              ->addColumn('pokemon2', 'binary', ['limit' => 59])
              ->addColumn('pokemon3', 'binary', ['limit' => 59])
              ->addColumn('message_start', 'binary', ['limit' => 8])
              ->addColumn('message_win', 'binary', ['limit' => 8])
              ->addColumn('message_lose', 'binary', ['limit' => 8])
              ->addColumn('num_trainers_defeated', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('num_turns_required', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('damage_taken', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('num_fainted_pokemon', 'integer', ['signed' => false, 'limit' => 3])
              ->create();

        // bxte_battle_tower_trainers
        $table = $this->table('bxte_battle_tower_trainers', ['id' => false, 'primary_key' => ['no', 'room', 'level']]);
        $table->addColumn('no', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('name', 'binary', ['limit' => 7])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 59])
              ->addColumn('pokemon2', 'binary', ['limit' => 59])
              ->addColumn('pokemon3', 'binary', ['limit' => 59])
              ->addColumn('message_start', 'binary', ['limit' => 8])
              ->addColumn('message_win', 'binary', ['limit' => 8])
              ->addColumn('message_lose', 'binary', ['limit' => 8])
              ->create();

        // bxte_battle_tower_leaders
        $table = $this->table('bxte_battle_tower_leaders', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('name', 'binary', ['limit' => 7])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->create();

        // bxte_ranking
        $table = $this->table('bxte_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtdTables(): void
    {
        // bxtd_ranking
        $table = $this->table('bxtd_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtfTables(): void
    {
        // bxtf_ranking
        $table = $this->table('bxtf_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtiTables(): void
    {
        // bxti_ranking
        $table = $this->table('bxti_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtpTables(): void
    {
        // bxtp_ranking
        $table = $this->table('bxtp_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtsTables(): void
    {
        // bxts_ranking
        $table = $this->table('bxts_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtuTables(): void
    {
        // bxtu_ranking
        $table = $this->table('bxtu_ranking', ['id' => false, 'primary_key' => ['news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 8])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function createBxtGeneralTables(): void
    {
        // bxt_exchange
        $table = $this->table('bxt_exchange', ['id' => false]);
        $table->addColumn('entry_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'Current time at entry.'])
              ->addColumn('email', 'string', ['limit' => 30, 'comment' => 'DION email'])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('game_region', 'char', ['limit' => 1])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
              ->addColumn('offer_gender', 'integer', ['signed' => false, 'limit' => 1, 'comment' => 'Gender of Pokémon'])
              ->addColumn('offer_species', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Decimal Pokémon ID.'])
              ->addColumn('request_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('request_species', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('trainer_name', 'binary', ['limit' => 7, 'comment' => 'Name of player'])
              ->addColumn('pokemon', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
              ->addColumn('mail', 'binary', ['limit' => 47, 'comment' => 'Held mail of Pokémon'])
              ->addIndex(['account_id', 'trainer_id', 'secret_id'], ['unique' => true, 'name' => 'UNIQUE'])
              ->create();

        // bxt_ranking_categories
        $table = $this->table('bxt_ranking_categories', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['signed' => false, 'limit' => 2])
              ->addColumn('name', 'string', ['limit' => 80])
              ->addColumn('ram_address', 'binary', ['limit' => 2])
              ->addColumn('size', 'integer', ['signed' => false, 'limit' => 1])
              ->create();

        // Insert ranking categories data
        $data = [
            ['id' => 0, 'name' => 'Play time when last entered the Hall of Fame', 'ram_address' => hex2bin('01A0'), 'size' => 4],
            ['id' => 1, 'name' => 'Step count when last entered the Hall of Fame', 'ram_address' => hex2bin('05A0'), 'size' => 4],
            ['id' => 2, 'name' => 'Number of times the party was healed when last entered the Hall of Fame', 'ram_address' => hex2bin('09A0'), 'size' => 3],
            ['id' => 3, 'name' => 'Number of battles when last entered the Hall of Fame', 'ram_address' => hex2bin('0DA0'), 'size' => 3],
            ['id' => 4, 'name' => 'Step count', 'ram_address' => hex2bin('10A0'), 'size' => 4],
            ['id' => 5, 'name' => 'Number of Battle Tower wins', 'ram_address' => hex2bin('14A0'), 'size' => 2],
            ['id' => 6, 'name' => 'Number of times TMs and HMs have been taught', 'ram_address' => hex2bin('18A0'), 'size' => 3],
            ['id' => 7, 'name' => 'Number of battles', 'ram_address' => hex2bin('1BA0'), 'size' => 3],
            ['id' => 8, 'name' => 'Number of wild Pokémon battles', 'ram_address' => hex2bin('1EA0'), 'size' => 3],
            ['id' => 9, 'name' => 'Number of Trainer battles', 'ram_address' => hex2bin('21A0'), 'size' => 3],
            ['id' => 10, 'name' => 'Unused', 'ram_address' => hex2bin('24A0'), 'size' => 3],
            ['id' => 11, 'name' => 'Number of Hall of Fame inductions', 'ram_address' => hex2bin('27A0'), 'size' => 3],
            ['id' => 12, 'name' => 'Number of wild Pokémon caught', 'ram_address' => hex2bin('2AA0'), 'size' => 3],
            ['id' => 13, 'name' => 'Number of hooked Pokémon encounters', 'ram_address' => hex2bin('2DA0'), 'size' => 3],
            ['id' => 14, 'name' => 'Number of Eggs hatched', 'ram_address' => hex2bin('30A0'), 'size' => 3],
            ['id' => 15, 'name' => 'Number of Pokémon evolved', 'ram_address' => hex2bin('33A0'), 'size' => 3],
            ['id' => 16, 'name' => 'Number of Berries and Apricorns picked', 'ram_address' => hex2bin('36A0'), 'size' => 3],
            ['id' => 17, 'name' => 'Number of times the party is healed', 'ram_address' => hex2bin('39A0'), 'size' => 3],
            ['id' => 18, 'name' => 'Number of times Mystery Gift is used', 'ram_address' => hex2bin('3CA0'), 'size' => 3],
            ['id' => 19, 'name' => 'Number of trades', 'ram_address' => hex2bin('3FA0'), 'size' => 3],
            ['id' => 20, 'name' => 'Number of uses of field move Fly', 'ram_address' => hex2bin('42A0'), 'size' => 3],
            ['id' => 21, 'name' => 'Number of uses of field move Surf', 'ram_address' => hex2bin('45A0'), 'size' => 3],
            ['id' => 22, 'name' => 'Number of uses of field move Waterfall', 'ram_address' => hex2bin('48A0'), 'size' => 3],
            ['id' => 23, 'name' => 'Number of times the player whited out', 'ram_address' => hex2bin('4BA0'), 'size' => 3],
            ['id' => 24, 'name' => 'Number of Lucky Number Show prizes won', 'ram_address' => hex2bin('4EA0'), 'size' => 3],
            ['id' => 25, 'name' => 'Number of Phone calls made and received', 'ram_address' => hex2bin('51A0'), 'size' => 3],
            ['id' => 26, 'name' => 'Unused', 'ram_address' => hex2bin('54A0'), 'size' => 3],
            ['id' => 27, 'name' => 'Number of Colosseum battles', 'ram_address' => hex2bin('57A0'), 'size' => 3],
            ['id' => 28, 'name' => 'Number of times players Pokémon used Splash', 'ram_address' => hex2bin('5AA0'), 'size' => 3],
            ['id' => 29, 'name' => 'Number of tree Pokémon encounters', 'ram_address' => hex2bin('5DA0'), 'size' => 3],
            ['id' => 30, 'name' => 'Unused', 'ram_address' => hex2bin('60A0'), 'size' => 3],
            ['id' => 31, 'name' => 'Number of Colosseum wins', 'ram_address' => hex2bin('63A0'), 'size' => 3],
            ['id' => 32, 'name' => 'Number of Colosseum losses', 'ram_address' => hex2bin('66A0'), 'size' => 3],
            ['id' => 33, 'name' => 'Number of Colosseum ties', 'ram_address' => hex2bin('69A0'), 'size' => 3],
            ['id' => 34, 'name' => 'Number of times players Pokémon used SelfDestruct or Explosion', 'ram_address' => hex2bin('6CA0'), 'size' => 3],
            ['id' => 35, 'name' => 'Current streak of consecutive slot machine wins', 'ram_address' => hex2bin('6FA0'), 'size' => 2],
            ['id' => 36, 'name' => 'Longest streak of consecutive slot machine wins', 'ram_address' => hex2bin('71A0'), 'size' => 2],
            ['id' => 37, 'name' => 'Total coins won from slot machines', 'ram_address' => hex2bin('73A0'), 'size' => 4],
            ['id' => 38, 'name' => 'Total money earned from battles (including Pay Day)', 'ram_address' => hex2bin('77A0'), 'size' => 4],
            ['id' => 39, 'name' => 'Largest Magikarp measured', 'ram_address' => hex2bin('7BA0'), 'size' => 2],
            ['id' => 40, 'name' => 'Smallest Magikarp measured', 'ram_address' => hex2bin('7DA0'), 'size' => 2],
            ['id' => 41, 'name' => 'Bug-Catching Contest high score', 'ram_address' => hex2bin('7FA0'), 'size' => 2],
        ];
        $this->table('bxt_ranking_categories')->insert($data)->saveData();

        // bxt_news
        $table = $this->table('bxt_news', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('ranking_category_1', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('ranking_category_2', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('ranking_category_3', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('message_j', 'varbinary', ['limit' => 100])
              ->addColumn('message_e', 'varbinary', ['limit' => 100])
              ->addColumn('message_d', 'varbinary', ['limit' => 100])
              ->addColumn('message_f', 'varbinary', ['limit' => 100])
              ->addColumn('message_i', 'varbinary', ['limit' => 100])
              ->addColumn('message_s', 'varbinary', ['limit' => 100])
              ->addColumn('news_binary_j', 'blob')
              ->addColumn('news_binary_e', 'blob')
              ->addColumn('news_binary_d', 'blob')
              ->addColumn('news_binary_f', 'blob')
              ->addColumn('news_binary_i', 'blob')
              ->addColumn('news_binary_s', 'blob')
              ->create();
    }

    private function createAmkjTables(): void
    {
        // amkj_user_map
        $table = $this->table('amkj_user_map', ['id' => false, 'primary_key' => 'player_id']);
        $table->addColumn('player_id', 'binary', ['limit' => 16])
              ->addColumn('user_id', 'integer', ['limit' => 11, 'null' => true])
              ->create();

        // amkj_rule
        $table = $this->table('amkj_rule', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'limit' => 11])
              ->addColumn('file_name', 'text')
              ->addColumn('start_date', 'date')
              ->addColumn('end_date', 'date')
              ->addColumn('next_start_date', 'date')
              ->addColumn('next_end_date', 'date')
              ->addColumn('entry_start_date', 'date')
              ->addColumn('entry_end_date', 'date')
              ->addColumn('ranking_start_date', 'date')
              ->addColumn('ranking_end_date', 'date')
              ->addColumn('coins_enabled', 'boolean')
              ->addColumn('items_enabled', 'boolean')
              ->addColumn('start_item_triple_shroom_enabled', 'boolean')
              ->addColumn('shrooms_only_enabled', 'boolean')
              ->addColumn('cpu_enabled', 'boolean')
              ->addColumn('character', 'integer', ['limit' => 1])
              ->addColumn('start_coins', 'integer', ['limit' => 1])
              ->addColumn('five_laps_enabled', 'boolean')
              ->addColumn('course', 'integer', ['limit' => 2])
              ->addColumn('num_attempts', 'integer', ['limit' => 2])
              ->addColumn('message', 'text', ['null' => true])
              ->create();

        // amkj_ghosts
        $table = $this->table('amkj_ghosts', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('player_id', 'binary', ['limit' => 16])
              ->addColumn('course_no', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('driver', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('name', 'binary', ['limit' => 5])
              ->addColumn('state', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('unk18', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('course', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('time', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('input_data', 'blob')
              ->addColumn('full_name', 'binary', ['limit' => 16])
              ->addColumn('phone_number', 'binary', ['limit' => 12])
              ->addColumn('postal_code', 'binary', ['limit' => 8])
              ->addColumn('address', 'binary', ['limit' => 128])
              ->create();

        // amkj_ghosts_mobilegp
        $table = $this->table('amkj_ghosts_mobilegp', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('gp_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('player_id', 'binary', ['limit' => 16])
              ->addColumn('course_no', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('driver', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('name', 'binary', ['limit' => 5])
              ->addColumn('state', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('unk18', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('course', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('time', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('input_data', 'blob')
              ->addColumn('full_name', 'binary', ['limit' => 16])
              ->addColumn('phone_number', 'binary', ['limit' => 12])
              ->addColumn('postal_code', 'binary', ['limit' => 8])
              ->addColumn('address', 'binary', ['limit' => 128])
              ->create();
    }

    private function createAmojTables(): void
    {
        // amoj_ranking
        $table = $this->table('amoj_ranking', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('name', 'binary', ['limit' => 4])
              ->addColumn('email', 'string', ['limit' => 32])
              ->addColumn('today', 'integer', ['signed' => false, 'limit' => 3, 'default' => 0])
              ->addColumn('points', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('money', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('gender', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('state', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('today2', 'integer', ['signed' => false, 'limit' => 3, 'default' => 0])
              ->create();

        // amoj_news
        $table = $this->table('amoj_news', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('text', 'text', ['limit' => 65535])
              ->create();
    }
}
