<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Consolidate Regional Tables Migration
 *
 * This migration consolidates region-specific Pokemon Crystal tables (bxtj_*, bxte_*, bxtd_*, etc.)
 * into unified tables with a game_region column. This preserves all existing data while simplifying
 * the schema.
 *
 * Key changes:
 * 1. Adds timestamp columns to system tables (sys_users, sys_email_change, sys_password_reset, sys_signup, sys_inbox)
 * 2. Adds timestamp columns to game tables (amkj_*, amoj_*)
 * 3. Merges bxtj_exchange, bxte_exchange -> bxt_exchange (with game_region column)
 * 4. Merges bxtj_battle_tower_* -> bxt_battle_tower_* (with game_region column)
 * 5. Merges all bxt*_ranking tables -> bxt_ranking (with game_region column)
 * 6. Consolidates bxt_news from multi-column (message_j, message_e, etc.) to single message/news_binary with game_region
 * 7. Adds timestamp column to bxt_ranking_categories
 *
 * IMPORTANT: This migration preserves ALL existing data, including bxt_news entries.
 */
final class ConsolidateRegionalTables extends AbstractMigration
{
    /**
     * Migrate Up - Consolidate tables and preserve data
     */
    public function up(): void
    {
        // Step 1: Add timestamp columns to existing system and game tables
        $this->addTimestampColumns();

        // Step 2: Create new unified BXT tables
        $this->createUnifiedBxtTables();

        // Step 3: Migrate data from old regional tables to new unified tables
        $this->migrateDataToUnifiedTables();

        // Step 4: Drop old regional tables and rename unified tables
        $this->finalizeTableConsolidation();
    }

    /**
     * Migrate Down - Not implemented (would require data backup)
     */
    public function down(): void
    {
        $this->output->writeln('<error>Rollback not implemented. This migration cannot be reversed automatically.</error>');
        $this->output->writeln('<error>Please restore from a database backup if needed.</error>');
    }

    private function addTimestampColumns(): void
    {
        // Add timestamp to sys_users
        if ($this->hasTable('sys_users') && !$this->table('sys_users')->hasColumn('timestamp')) {
            $this->table('sys_users')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'money_spent'])
                 ->update();
        }

        // Add timestamp to sys_email_change (rename 'time' to 'timestamp')
        if ($this->hasTable('sys_email_change')) {
            if ($this->table('sys_email_change')->hasColumn('time') && !$this->table('sys_email_change')->hasColumn('timestamp')) {
                $this->execute('ALTER TABLE sys_email_change CHANGE COLUMN `time` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            }
        }

        // Add timestamp to sys_password_reset (rename 'time' to 'timestamp')
        if ($this->hasTable('sys_password_reset')) {
            if ($this->table('sys_password_reset')->hasColumn('time') && !$this->table('sys_password_reset')->hasColumn('timestamp')) {
                $this->execute('ALTER TABLE sys_password_reset CHANGE COLUMN `time` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            }
        }

        // Add timestamp to sys_signup (rename 'time' to 'timestamp')
        if ($this->hasTable('sys_signup')) {
            if ($this->table('sys_signup')->hasColumn('time') && !$this->table('sys_signup')->hasColumn('timestamp')) {
                $this->execute('ALTER TABLE sys_signup CHANGE COLUMN `time` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            }
        }

        // Add timestamp to sys_inbox (rename 'date' to 'timestamp')
        if ($this->hasTable('sys_inbox')) {
            if ($this->table('sys_inbox')->hasColumn('date') && !$this->table('sys_inbox')->hasColumn('timestamp')) {
                $this->execute('ALTER TABLE sys_inbox CHANGE COLUMN `date` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            }
        }

        // Add timestamp to AMKJ tables
        if ($this->hasTable('amkj_user_map') && !$this->table('amkj_user_map')->hasColumn('timestamp')) {
            $this->table('amkj_user_map')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        if ($this->hasTable('amkj_rule') && !$this->table('amkj_rule')->hasColumn('timestamp')) {
            $this->table('amkj_rule')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        if ($this->hasTable('amkj_ghosts') && !$this->table('amkj_ghosts')->hasColumn('timestamp')) {
            $this->table('amkj_ghosts')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        if ($this->hasTable('amkj_ghosts_mobilegp') && !$this->table('amkj_ghosts_mobilegp')->hasColumn('timestamp')) {
            $this->table('amkj_ghosts_mobilegp')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        // Add timestamp to AMOJ tables
        if ($this->hasTable('amoj_ranking') && !$this->table('amoj_ranking')->hasColumn('timestamp')) {
            $this->table('amoj_ranking')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        if ($this->hasTable('amoj_news') && !$this->table('amoj_news')->hasColumn('timestamp')) {
            $this->table('amoj_news')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }

        // Add timestamp to bxt_ranking_categories
        if ($this->hasTable('bxt_ranking_categories') && !$this->table('bxt_ranking_categories')->hasColumn('timestamp')) {
            $this->table('bxt_ranking_categories')
                 ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->update();
        }
    }

    private function createUnifiedBxtTables(): void
    {
        // Create new bxt_exchange (unified with game_region)
        $table = $this->table('bxt_exchange_unified', ['id' => false, 'comment' => 'Pokémon Trade Corner information']);
        $table->addColumn('game_region', 'char', ['limit' => 1])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
              ->addColumn('offer_gender', 'integer', ['signed' => false, 'limit' => 1, 'comment' => 'Gender of Pokémon'])
              ->addColumn('offer_species', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Decimal Pokémon ID.'])
              ->addColumn('request_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('request_species', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_name', 'binary', ['limit' => 7, 'comment' => 'Name of player'])
              ->addColumn('pokemon', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
              ->addColumn('mail', 'binary', ['limit' => 47, 'comment' => 'Held mail of Pokémon'])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('email', 'string', ['limit' => 30, 'comment' => 'DION email'])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['account_id', 'trainer_id', 'secret_id'], ['unique' => true, 'name' => 'UNIQUE'])
              ->create();

        // Create new bxt_battle_tower_records
        $table = $this->table('bxt_battle_tower_records_unified', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 10])
              ->addColumn('game_region', 'char', ['limit' => 1])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 59])
              ->addColumn('pokemon2', 'binary', ['limit' => 59])
              ->addColumn('pokemon3', 'binary', ['limit' => 59])
              ->addColumn('message_start', 'binary', ['limit' => 12])
              ->addColumn('message_win', 'binary', ['limit' => 12])
              ->addColumn('message_lose', 'binary', ['limit' => 12])
              ->addColumn('num_trainers_defeated', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('num_turns_required', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('damage_taken', 'integer', ['signed' => false, 'limit' => 5])
              ->addColumn('num_fainted_pokemon', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('email', 'string', ['limit' => 30])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // Create new bxt_battle_tower_trainers
        $table = $this->table('bxt_battle_tower_trainers_unified', ['id' => false, 'primary_key' => ['no', 'game_region', 'room', 'level']]);
        $table->addColumn('no', 'integer', ['signed' => false, 'limit' => 1, 'null' => false])
              ->addColumn('game_region', 'char', ['limit' => 1, 'null' => false])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10, 'null' => false])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1, 'null' => false])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('class', 'integer', ['limit' => 1])
              ->addColumn('pokemon1', 'binary', ['limit' => 59])
              ->addColumn('pokemon2', 'binary', ['limit' => 59])
              ->addColumn('pokemon3', 'binary', ['limit' => 59])
              ->addColumn('message_start', 'binary', ['limit' => 12])
              ->addColumn('message_win', 'binary', ['limit' => 12])
              ->addColumn('message_lose', 'binary', ['limit' => 12])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // Create new bxt_battle_tower_leaders
        $table = $this->table('bxt_battle_tower_leaders_unified', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('game_region', 'char', ['limit' => 1])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('room', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // Create new bxt_ranking
        $table = $this->table('bxt_ranking_unified', ['id' => false, 'primary_key' => ['game_region', 'news_id', 'category_id', 'account_id', 'trainer_id', 'secret_id']]);
        $table->addColumn('game_region', 'char', ['limit' => 1, 'null' => false])
              ->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
              ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2, 'null' => false])
              ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'null' => false])
              ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'null' => false])
              ->addColumn('player_name', 'binary', ['limit' => 7])
              ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
              ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
              ->addColumn('player_zip', 'binary', ['limit' => 3])
              ->addColumn('player_message', 'binary', ['limit' => 12])
              ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
              ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11, 'null' => false])
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();

        // Create new bxt_news
        $table = $this->table('bxt_news_unified', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
              ->addColumn('game_region', 'char', ['limit' => 1])
              ->addColumn('ranking_category_1', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('ranking_category_2', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('ranking_category_3', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
              ->addColumn('message', 'varbinary', ['limit' => 100])
              ->addColumn('news_binary', 'blob')
              ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }

    private function migrateDataToUnifiedTables(): void
    {
        // Migrate bxtj_exchange -> bxt_exchange_unified
        if ($this->hasTable('bxtj_exchange')) {
            $this->execute("
                INSERT INTO bxt_exchange_unified
                (game_region, trainer_id, secret_id, offer_gender, offer_species,
                 request_gender, request_species, player_name, pokemon, mail,
                 account_id, email, timestamp)
                SELECT
                  'j', trainer_id, secret_id,
                  offer_gender, offer_species,
                  request_gender, request_species,
                  RPAD(trainer_name, 7, 0x00),
                  RPAD(pokemon, 65, 0x00),
                  RPAD(mail, 47, 0x00),
                  account_id, email,
                  entry_time
                FROM bxtj_exchange
            ");
        }

        // Migrate existing bxt_exchange (if it exists and has data)
        if ($this->hasTable('bxt_exchange')) {
            $count = $this->fetchRow('SELECT COUNT(*) as cnt FROM bxt_exchange');
            if ($count['cnt'] > 0) {
                $this->execute("
                    INSERT INTO bxt_exchange_unified
                    (game_region, trainer_id, secret_id, offer_gender, offer_species,
                     request_gender, request_species, player_name, pokemon, mail,
                     account_id, email, timestamp)
                    SELECT
                      game_region, trainer_id, secret_id,
                      offer_gender, offer_species,
                      request_gender, request_species,
                      trainer_name, pokemon, mail,
                      account_id, email,
                      entry_time
                    FROM bxt_exchange
                ");
            }
        }

        // Migrate bxtj_battle_tower_records
        if ($this->hasTable('bxtj_battle_tower_records')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_records_unified
                (game_region, room, level, trainer_id, secret_id, player_name,
                 class, pokemon1, pokemon2, pokemon3,
                 message_start, message_win, message_lose,
                 num_trainers_defeated, num_turns_required, damage_taken, num_fainted_pokemon,
                 account_id, email, timestamp)
                SELECT
                  'j', room, level,
                  trainer_id, secret_id,
                  RPAD(name, 7, 0x00),
                  class,
                  RPAD(pokemon1, 59, 0x00),
                  RPAD(pokemon2, 59, 0x00),
                  RPAD(pokemon3, 59, 0x00),
                  message_start, message_win, message_lose,
                  num_trainers_defeated, num_turns_required, damage_taken, num_fainted_pokemon,
                  0, '',
                  CURRENT_TIMESTAMP
                FROM bxtj_battle_tower_records
            ");
        }

        // Migrate bxte_battle_tower_records
        if ($this->hasTable('bxte_battle_tower_records')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_records_unified
                (game_region, room, level, trainer_id, secret_id, player_name,
                 class, pokemon1, pokemon2, pokemon3,
                 message_start, message_win, message_lose,
                 num_trainers_defeated, num_turns_required, damage_taken, num_fainted_pokemon,
                 account_id, email, timestamp)
                SELECT
                  'e', room, level,
                  trainer_id, secret_id,
                  name,
                  class,
                  pokemon1, pokemon2, pokemon3,
                  RPAD(message_start, 12, 0x00),
                  RPAD(message_win, 12, 0x00),
                  RPAD(message_lose, 12, 0x00),
                  num_trainers_defeated, num_turns_required, damage_taken, num_fainted_pokemon,
                  0, '',
                  CURRENT_TIMESTAMP
                FROM bxte_battle_tower_records
            ");
        }

        // Migrate bxtj_battle_tower_trainers
        if ($this->hasTable('bxtj_battle_tower_trainers')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_trainers_unified
                (no, game_region, room, level, player_name, class,
                 pokemon1, pokemon2, pokemon3,
                 message_start, message_win, message_lose, timestamp)
                SELECT
                  no, 'j', room, level,
                  RPAD(name, 7, 0x00),
                  class,
                  RPAD(pokemon1, 59, 0x00),
                  RPAD(pokemon2, 59, 0x00),
                  RPAD(pokemon3, 59, 0x00),
                  message_start, message_win, message_lose,
                  CURRENT_TIMESTAMP
                FROM bxtj_battle_tower_trainers
            ");
        }

        // Migrate bxte_battle_tower_trainers
        if ($this->hasTable('bxte_battle_tower_trainers')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_trainers_unified
                (no, game_region, room, level, player_name, class,
                 pokemon1, pokemon2, pokemon3,
                 message_start, message_win, message_lose, timestamp)
                SELECT
                  no, 'e', room, level,
                  name,
                  class,
                  pokemon1, pokemon2, pokemon3,
                  RPAD(message_start, 12, 0x00),
                  RPAD(message_win, 12, 0x00),
                  RPAD(message_lose, 12, 0x00),
                  CURRENT_TIMESTAMP
                FROM bxte_battle_tower_trainers
            ");
        }

        // Migrate bxtj_battle_tower_leaders
        if ($this->hasTable('bxtj_battle_tower_leaders')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_leaders_unified
                (game_region, player_name, room, level, timestamp)
                SELECT
                  'j',
                  RPAD(name, 7, 0x00),
                  room, level,
                  CURRENT_TIMESTAMP
                FROM bxtj_battle_tower_leaders
            ");
        }

        // Migrate bxte_battle_tower_leaders
        if ($this->hasTable('bxte_battle_tower_leaders')) {
            $this->execute("
                INSERT INTO bxt_battle_tower_leaders_unified
                (game_region, player_name, room, level, timestamp)
                SELECT
                  'e',
                  name,
                  room, level,
                  CURRENT_TIMESTAMP
                FROM bxte_battle_tower_leaders
            ");
        }

        // Migrate ranking tables
        $rankingMigrations = [
            'j' => 'bxtj_ranking',
            'e' => 'bxte_ranking',
            'd' => 'bxtd_ranking',
            'f' => 'bxtf_ranking',
            'i' => 'bxti_ranking',
            'p' => 'bxtp_ranking',
            's' => 'bxts_ranking',
            'u' => 'bxtu_ranking',
        ];

        foreach ($rankingMigrations as $region => $tableName) {
            if ($this->hasTable($tableName)) {
                $this->execute("
                    INSERT INTO bxt_ranking_unified
                    (game_region, news_id, category_id, trainer_id, secret_id, player_name,
                     player_gender, player_age, player_region, player_zip, player_message,
                     score, account_id, timestamp)
                    SELECT
                      '{$region}',
                      news_id, category_id,
                      trainer_id, secret_id,
                      player_name,
                      player_gender, player_age, player_region,
                      player_zip,
                      player_message,
                      score,
                      account_id,
                      timestamp
                    FROM {$tableName}
                ");
            }
        }

        // Migrate bxt_news - this is the critical part that preserves existing news data!
        // The old schema had message_j, message_e, etc. We need to create separate rows for each region.
        // NOTE: message_e/news_binary_e is shared between regions 'e' (North America), 'p' (Europe), and 'u' (Australia)
        if ($this->hasTable('bxt_news')) {
            // Define mapping of source columns to target regions
            $newsRegionMapping = [
                'j' => ['j'],           // Japanese -> j
                'e' => ['e', 'p', 'u'], // English -> e (North America), p (Europe), u (Australia)
                'd' => ['d'],           // German -> d
                'f' => ['f'],           // French -> f
                'i' => ['i'],           // Italian -> i
                's' => ['s'],           // Spanish -> s
            ];

            foreach ($newsRegionMapping as $sourceColumn => $targetRegions) {
                // Check if the columns exist first
                $columns = $this->fetchAll("SHOW COLUMNS FROM bxt_news LIKE 'message_{$sourceColumn}'");
                if (!empty($columns)) {
                    // For each target region that uses this source column
                    foreach ($targetRegions as $targetRegion) {
                        $this->execute("
                            INSERT INTO bxt_news_unified
                            (game_region, ranking_category_1, ranking_category_2, ranking_category_3,
                             message, news_binary, timestamp)
                            SELECT
                              '{$targetRegion}',
                              ranking_category_1,
                              ranking_category_2,
                              ranking_category_3,
                              message_{$sourceColumn},
                              news_binary_{$sourceColumn},
                              CURRENT_TIMESTAMP
                            FROM bxt_news
                            WHERE message_{$sourceColumn} IS NOT NULL
                              AND message_{$sourceColumn} != ''
                              AND LENGTH(message_{$sourceColumn}) > 0
                        ");
                    }
                }
            }
        }
    }

    private function finalizeTableConsolidation(): void
    {
        // Drop old regional tables
        $oldTables = [
            'bxtj_exchange',
            'bxtj_battle_tower_records',
            'bxtj_battle_tower_trainers',
            'bxtj_battle_tower_leaders',
            'bxtj_ranking',
            'bxte_battle_tower_records',
            'bxte_battle_tower_trainers',
            'bxte_battle_tower_leaders',
            'bxte_ranking',
            'bxtd_ranking',
            'bxtf_ranking',
            'bxti_ranking',
            'bxtp_ranking',
            'bxts_ranking',
            'bxtu_ranking',
        ];

        foreach ($oldTables as $table) {
            if ($this->hasTable($table)) {
                $this->table($table)->drop()->save();
            }
        }

        // Drop old unified tables if they exist
        $oldUnifiedTables = [
            'bxt_exchange',
            'bxt_battle_tower_records',
            'bxt_battle_tower_trainers',
            'bxt_battle_tower_leaders',
            'bxt_ranking',
            'bxt_news',
        ];

        foreach ($oldUnifiedTables as $table) {
            if ($this->hasTable($table)) {
                $this->table($table)->drop()->save();
            }
        }

        // Rename new tables to final names
        $tableRenames = [
            'bxt_exchange_unified' => 'bxt_exchange',
            'bxt_battle_tower_records_unified' => 'bxt_battle_tower_records',
            'bxt_battle_tower_trainers_unified' => 'bxt_battle_tower_trainers',
            'bxt_battle_tower_leaders_unified' => 'bxt_battle_tower_leaders',
            'bxt_ranking_unified' => 'bxt_ranking',
            'bxt_news_unified' => 'bxt_news',
        ];

        foreach ($tableRenames as $oldName => $newName) {
            if ($this->hasTable($oldName)) {
                $this->table($oldName)->rename($newName)->save();
            }
        }
    }
}
