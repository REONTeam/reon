<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdatePokemonCrystalTables extends AbstractMigration
{
    /**
     * Update Pokemon Crystal (BXT) tables with upstream changes:
     * - Add decode columns for human-readable versions of binary/encoded data
     * - Reorder columns for better organization
     * - Add indexes for rate limiting
     * - Add triggers for rate limiting (5 per 24h for battle tower records, 1 per 2h for exchange)
     * - Update ranking categories with shortened names
     */
    public function change(): void
    {
        // Update bxt_battle_tower_records table
        if ($this->hasTable('bxt_battle_tower_records')) {
            $table = $this->table('bxt_battle_tower_records');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('level_decode')) {
                $table->addColumn('level_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'level']);
            }
            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('class_decode')) {
                $table->addColumn('class_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'class']);
            }
            if (!$table->hasColumn('pokemon1_decode')) {
                $table->addColumn('pokemon1_decode', 'text', ['null' => true, 'after' => 'pokemon1']);
            }
            if (!$table->hasColumn('pokemon2_decode')) {
                $table->addColumn('pokemon2_decode', 'text', ['null' => true, 'after' => 'pokemon2']);
            }
            if (!$table->hasColumn('pokemon3_decode')) {
                $table->addColumn('pokemon3_decode', 'text', ['null' => true, 'after' => 'pokemon3']);
            }
            if (!$table->hasColumn('message_start_decode')) {
                $table->addColumn('message_start_decode', 'text', ['null' => true, 'after' => 'message_start']);
            }
            if (!$table->hasColumn('message_win_decode')) {
                $table->addColumn('message_win_decode', 'text', ['null' => true, 'after' => 'message_win']);
            }
            if (!$table->hasColumn('message_lose_decode')) {
                $table->addColumn('message_lose_decode', 'text', ['null' => true, 'after' => 'message_lose']);
            }
            if (!$table->hasIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'])) {
                $table->addIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'], ['name' => 'idx_limit_24h']);
            }
            $table->save();

            // Remove email column if it exists
            if ($table->hasColumn('email')) {
                $table->removeColumn('email')->save();
            }
        }

        // Update bxt_battle_tower_trainers table
        if ($this->hasTable('bxt_battle_tower_trainers')) {
            $table = $this->table('bxt_battle_tower_trainers');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('class_decode')) {
                $table->addColumn('class_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'class']);
            }
            if (!$table->hasColumn('pokemon1_decode')) {
                $table->addColumn('pokemon1_decode', 'text', ['null' => true, 'after' => 'pokemon1']);
            }
            if (!$table->hasColumn('pokemon2_decode')) {
                $table->addColumn('pokemon2_decode', 'text', ['null' => true, 'after' => 'pokemon2']);
            }
            if (!$table->hasColumn('pokemon3_decode')) {
                $table->addColumn('pokemon3_decode', 'text', ['null' => true, 'after' => 'pokemon3']);
            }
            if (!$table->hasColumn('message_start_decode')) {
                $table->addColumn('message_start_decode', 'text', ['null' => true, 'after' => 'message_start']);
            }
            if (!$table->hasColumn('message_win_decode')) {
                $table->addColumn('message_win_decode', 'text', ['null' => true, 'after' => 'message_win']);
            }
            if (!$table->hasColumn('message_lose_decode')) {
                $table->addColumn('message_lose_decode', 'text', ['null' => true, 'after' => 'message_lose']);
            }
            if (!$table->hasColumn('account_id')) {
                $table->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11, 'null' => false, 'after' => 'message_lose_decode']);
            }
            $table->save();
        }

        // Update bxt_battle_tower_leaders table
        if ($this->hasTable('bxt_battle_tower_leaders')) {
            $table = $this->table('bxt_battle_tower_leaders');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('level_decode')) {
                $table->addColumn('level_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'level']);
            }
            $table->save();
        }

        // Update bxt_exchange table
        if ($this->hasTable('bxt_exchange')) {
            $table = $this->table('bxt_exchange');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('offer_gender_decode')) {
                $table->addColumn('offer_gender_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'offer_gender']);
            }
            if (!$table->hasColumn('offer_species_decode')) {
                $table->addColumn('offer_species_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'offer_species']);
            }
            if (!$table->hasColumn('request_gender_decode')) {
                $table->addColumn('request_gender_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'request_gender']);
            }
            if (!$table->hasColumn('request_species_decode')) {
                $table->addColumn('request_species_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'request_species']);
            }
            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('pokemon_decode')) {
                $table->addColumn('pokemon_decode', 'text', ['null' => true, 'after' => 'pokemon']);
            }
            if (!$table->hasColumn('mail_decode')) {
                $table->addColumn('mail_decode', 'text', ['null' => true, 'after' => 'mail']);
            }
            if (!$table->hasIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'])) {
                $table->addIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'], ['name' => 'idx_limit_2h']);
            }
            $table->save();
        }

        // Update bxt_news table
        if ($this->hasTable('bxt_news')) {
            $table = $this->table('bxt_news');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('ranking_category_1_decode')) {
                $table->addColumn('ranking_category_1_decode', 'string', ['limit' => 80, 'null' => true, 'after' => 'ranking_category_1']);
            }
            if (!$table->hasColumn('ranking_category_2_decode')) {
                $table->addColumn('ranking_category_2_decode', 'string', ['limit' => 80, 'null' => true, 'after' => 'ranking_category_2']);
            }
            if (!$table->hasColumn('ranking_category_3_decode')) {
                $table->addColumn('ranking_category_3_decode', 'string', ['limit' => 80, 'null' => true, 'after' => 'ranking_category_3']);
            }
            if (!$table->hasColumn('message_decode')) {
                $table->addColumn('message_decode', 'text', ['null' => true, 'after' => 'message']);
            }
            $table->save();
        }

        // Update bxt_ranking table
        if ($this->hasTable('bxt_ranking')) {
            $table = $this->table('bxt_ranking');

            // Add new decode columns (only if they don't exist)
            if (!$table->hasColumn('category_id_decode')) {
                $table->addColumn('category_id_decode', 'string', ['limit' => 80, 'null' => true, 'after' => 'category_id']);
            }
            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('player_gender_decode')) {
                $table->addColumn('player_gender_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'player_gender']);
            }
            if (!$table->hasColumn('player_region_decode')) {
                $table->addColumn('player_region_decode', 'string', ['limit' => 64, 'null' => true, 'after' => 'player_region']);
            }
            if (!$table->hasColumn('player_zip_decode')) {
                $table->addColumn('player_zip_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'player_zip']);
            }
            if (!$table->hasColumn('player_message_decode')) {
                $table->addColumn('player_message_decode', 'text', ['null' => true, 'after' => 'player_message']);
            }
            $table->save();
        }

        // Update bxt_ranking_categories data with shortened names
        $this->execute("
            UPDATE bxt_ranking_categories SET name = 'LAST HOF RECORD' WHERE id = 0;
            UPDATE bxt_ranking_categories SET name = 'LAST HOF STEPS' WHERE id = 1;
            UPDATE bxt_ranking_categories SET name = 'LAST HOF HEALED' WHERE id = 2;
            UPDATE bxt_ranking_categories SET name = 'LAST HOF BATTLES' WHERE id = 3;
            UPDATE bxt_ranking_categories SET name = 'STEPS WALKED' WHERE id = 4;
            UPDATE bxt_ranking_categories SET name = 'BATTLE TOWER WINS' WHERE id = 5;
            UPDATE bxt_ranking_categories SET name = 'TMs/HMs TAUGHT' WHERE id = 6;
            UPDATE bxt_ranking_categories SET name = 'POKéMON BATTLES' WHERE id = 7;
            UPDATE bxt_ranking_categories SET name = 'POKéMON ENCOUNTER' WHERE id = 8;
            UPDATE bxt_ranking_categories SET name = 'TRAINER BATTLES' WHERE id = 9;
            UPDATE bxt_ranking_categories SET name = 'UNUSED' WHERE id = 10;
            UPDATE bxt_ranking_categories SET name = 'HOF ENTRIES' WHERE id = 11;
            UPDATE bxt_ranking_categories SET name = 'POKéMON CAUGHT' WHERE id = 12;
            UPDATE bxt_ranking_categories SET name = 'POKéMON HOOKED' WHERE id = 13;
            UPDATE bxt_ranking_categories SET name = 'EGGS HATCHED' WHERE id = 14;
            UPDATE bxt_ranking_categories SET name = 'POKéMON EVOLVED' WHERE id = 15;
            UPDATE bxt_ranking_categories SET name = 'FRUIT PICKED' WHERE id = 16;
            UPDATE bxt_ranking_categories SET name = 'PARTY HEALED' WHERE id = 17;
            UPDATE bxt_ranking_categories SET name = 'MYSTERY GIFT USED' WHERE id = 18;
            UPDATE bxt_ranking_categories SET name = 'TRADES COMPLETED' WHERE id = 19;
            UPDATE bxt_ranking_categories SET name = 'FLY USED' WHERE id = 20;
            UPDATE bxt_ranking_categories SET name = 'SURF USED' WHERE id = 21;
            UPDATE bxt_ranking_categories SET name = 'WATERFALL USED' WHERE id = 22;
            UPDATE bxt_ranking_categories SET name = 'TIMES WHITED OUT' WHERE id = 23;
            UPDATE bxt_ranking_categories SET name = 'LUCKY NUMBER WINS' WHERE id = 24;
            UPDATE bxt_ranking_categories SET name = 'TOTAL PHONE CALLS' WHERE id = 25;
            UPDATE bxt_ranking_categories SET name = 'UNUSED' WHERE id = 26;
            UPDATE bxt_ranking_categories SET name = 'COLOSSEUM BATTLES' WHERE id = 27;
            UPDATE bxt_ranking_categories SET name = 'SPLASH USED' WHERE id = 28;
            UPDATE bxt_ranking_categories SET name = 'HEADBUTT USED' WHERE id = 29;
            UPDATE bxt_ranking_categories SET name = 'UNUSED' WHERE id = 30;
            UPDATE bxt_ranking_categories SET name = 'COLOSSEUM WINS' WHERE id = 31;
            UPDATE bxt_ranking_categories SET name = 'COLOSSEUM LOSSES' WHERE id = 32;
            UPDATE bxt_ranking_categories SET name = 'COLOSSEUM DRAWS' WHERE id = 33;
            UPDATE bxt_ranking_categories SET name = 'SELF-KO MOVE USED' WHERE id = 34;
            UPDATE bxt_ranking_categories SET name = 'SLOT WIN STREAK' WHERE id = 35;
            UPDATE bxt_ranking_categories SET name = 'BEST SLOT STREAK' WHERE id = 36;
            UPDATE bxt_ranking_categories SET name = 'SLOT COINS WON' WHERE id = 37;
            UPDATE bxt_ranking_categories SET name = 'TOTAL MONEY' WHERE id = 38;
            UPDATE bxt_ranking_categories SET name = 'LARGEST MAGIKARP' WHERE id = 39;
            UPDATE bxt_ranking_categories SET name = 'SMALLEST MAGIKARP' WHERE id = 40;
            UPDATE bxt_ranking_categories SET name = 'BUG CONTEST SCORE' WHERE id = 41;
        ");

        // Add triggers for rate limiting
        // Note: Trigger creation may fail if the database user lacks SUPER privilege
        // and binary logging is enabled. In that case, triggers must be created manually
        // by a database administrator or by setting log_bin_trust_function_creators=1

        try {
            // Add trigger for bxt_battle_tower_records (5 per 24 hours limit)
            $this->execute("DROP TRIGGER IF EXISTS bxt_battle_tower_records_limit_5_per_24h");

            $this->execute("
                CREATE DEFINER=CURRENT_USER TRIGGER bxt_battle_tower_records_limit_5_per_24h
                BEFORE INSERT ON bxt_battle_tower_records
                FOR EACH ROW
                BEGIN
                    DECLARE i INT;

                    SELECT COUNT(*)
                      INTO i
                      FROM bxt_battle_tower_records
                     WHERE account_id = NEW.account_id
                       AND trainer_id = NEW.trainer_id
                       AND secret_id = NEW.secret_id
                       AND `timestamp` >= NOW() - INTERVAL 24 HOUR;

                    IF i >= 5 THEN
                        SIGNAL SQLSTATE '45000'
                          SET MESSAGE_TEXT = 'Limit of 5 entries per 24 hours exceeded';
                    END IF;
                END
            ");
        } catch (\Exception $e) {
            $this->output->writeln('<warning>Warning: Could not create trigger bxt_battle_tower_records_limit_5_per_24h</warning>');
            $this->output->writeln('<warning>Error: ' . $e->getMessage() . '</warning>');
            $this->output->writeln('<warning>Rate limiting for battle tower records must be implemented in application code</warning>');
        }

        try {
            // Add trigger for bxt_exchange (1 per 2 hours limit)
            $this->execute("DROP TRIGGER IF EXISTS bxt_exchange_limit_1_per_2h");

            $this->execute("
                CREATE DEFINER=CURRENT_USER TRIGGER bxt_exchange_limit_1_per_2h
                BEFORE INSERT ON bxt_exchange
                FOR EACH ROW
                BEGIN
                    DECLARE i INT;

                    SELECT COUNT(*)
                      INTO i
                      FROM bxt_exchange
                     WHERE account_id = NEW.account_id
                       AND trainer_id = NEW.trainer_id
                       AND secret_id = NEW.secret_id
                       AND `timestamp` >= NOW() - INTERVAL 2 HOUR;

                    IF i >= 1 THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'Limit of 1 entry per 2 hours exceeded';
                    END IF;
                END
            ");
        } catch (\Exception $e) {
            $this->output->writeln('<warning>Warning: Could not create trigger bxt_exchange_limit_1_per_2h</warning>');
            $this->output->writeln('<warning>Error: ' . $e->getMessage() . '</warning>');
            $this->output->writeln('<warning>Rate limiting for exchange must be implemented in application code</warning>');
        }
    }
}
