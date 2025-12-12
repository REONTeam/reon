<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdatePokemonCrystalTables extends AbstractMigration
{
    /**
     * Update Pokemon Crystal (BXT) tables with upstream changes:
     * - Ensure core shared BXT tables exist with the canonical layout (tables.sql)
     * - Add missing columns / decode columns in an idempotent way
     * - Add indexes for rate limiting
     * - Add triggers for rate limiting (5 per 24h for battle tower records)
     * - Update ranking categories with shortened names
     */
    public function change(): void
    {
        /**
         * 1) CREATE-IF-MISSING BRANCHES
         *    These match the canonical schemas from tables.sql.
         */

        // bxt_battle_tower_records
        if (!$this->hasTable('bxt_battle_tower_records')) {
            $table = $this->table('bxt_battle_tower_records', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 10])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('room', 'integer', ['signed' => false, 'limit' => 10])
                  ->addColumn('level', 'integer', ['signed' => false, 'limit' => 2, 'comment' => 'Battle tower level'])
                  ->addColumn('level_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
                  ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
                  ->addColumn('player_name', 'binary', ['limit' => 7, 'comment' => 'Name of trainer'])
                  ->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('class', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Class of trainer'])
                  ->addColumn('class_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('pokemon1', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon1_decode', 'text', ['null' => true])
                  ->addColumn('pokemon2', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon2_decode', 'text', ['null' => true])
                  ->addColumn('pokemon3', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon3_decode', 'text', ['null' => true])
                  ->addColumn('message_start', 'binary', ['limit' => 12])
                  ->addColumn('message_start_decode', 'text', ['null' => true])
                  ->addColumn('message_win', 'binary', ['limit' => 12])
                  ->addColumn('message_win_decode', 'text', ['null' => true])
                  ->addColumn('message_lose', 'binary', ['limit' => 12])
                  ->addColumn('message_lose_decode', 'text', ['null' => true])
                  ->addColumn('num_trainers_defeated', 'integer', ['signed' => false, 'limit' => 3])
                  ->addColumn('num_turns_required', 'integer', ['signed' => false, 'limit' => 5])
                  ->addColumn('damage_taken', 'integer', ['signed' => false, 'limit' => 5])
                  ->addColumn('num_fainted_pokemon', 'integer', ['signed' => false, 'limit' => 3])
                  ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->create();
        }

        // bxt_battle_tower_trainers
        if (!$this->hasTable('bxt_battle_tower_trainers')) {
            $table = $this->table('bxt_battle_tower_trainers', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
                  ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
                  ->addColumn('player_name', 'binary', ['limit' => 7, 'comment' => 'Name of trainer'])
                  ->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('class', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Class of trainer'])
                  ->addColumn('class_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('pokemon1', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon1_decode', 'text', ['null' => true])
                  ->addColumn('pokemon2', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon2_decode', 'text', ['null' => true])
                  ->addColumn('pokemon3', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon3_decode', 'text', ['null' => true])
                  ->addColumn('message_start', 'binary', ['limit' => 12])
                  ->addColumn('message_start_decode', 'text', ['null' => true])
                  ->addColumn('message_win', 'binary', ['limit' => 12])
                  ->addColumn('message_win_decode', 'text', ['null' => true])
                  ->addColumn('message_lose', 'binary', ['limit' => 12])
                  ->addColumn('message_lose_decode', 'text', ['null' => true])
                  ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->create();
        }

        // bxt_battle_tower_leaders
        if (!$this->hasTable('bxt_battle_tower_leaders')) {
            $table = $this->table('bxt_battle_tower_leaders', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('player_name', 'binary', ['limit' => 7])
                  ->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('room', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('level', 'integer', ['signed' => false, 'limit' => 1])
                  ->addColumn('level_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->create();
        }

        // bxt_exchange
        if (!$this->hasTable('bxt_exchange')) {
            $table = $this->table('bxt_exchange', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Trainer ID'])
                  ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5, 'comment' => 'Secret ID'])
                  ->addColumn('offer_gender', 'integer', ['signed' => false, 'limit' => 1, 'comment' => 'Gender of Pokémon'])
                  ->addColumn('offer_gender_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('offer_species', 'integer', ['signed' => false, 'limit' => 3, 'comment' => 'Decimal Pokémon ID.'])
                  ->addColumn('offer_species_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('request_gender', 'integer', ['signed' => false, 'limit' => 1])
                  ->addColumn('request_gender_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('request_species', 'integer', ['signed' => false, 'limit' => 3])
                  ->addColumn('request_species_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('player_name', 'binary', ['limit' => 7, 'comment' => 'Name of player'])
                  ->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('pokemon', 'binary', ['limit' => 65, 'comment' => 'Pokémon'])
                  ->addColumn('pokemon_decode', 'text', ['null' => true])
                  ->addColumn('mail', 'binary', ['limit' => 47, 'comment' => 'Held mail of Pokémon'])
                  ->addColumn('mail_decode', 'text', ['null' => true])
                  ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('email', 'string', ['limit' => 30, 'comment' => 'DION email'])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->addIndex(['account_id', 'trainer_id', 'secret_id'], ['unique' => true, 'name' => 'UNIQUE'])
                  ->create();
        }

        // bxt_news
        if (!$this->hasTable('bxt_news')) {
            $table = $this->table('bxt_news', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('ranking_category_1', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
                  ->addColumn('ranking_category_1_decode', 'string', ['limit' => 80, 'null' => true])
                  ->addColumn('ranking_category_2', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
                  ->addColumn('ranking_category_2_decode', 'string', ['limit' => 80, 'null' => true])
                  ->addColumn('ranking_category_3', 'integer', ['signed' => false, 'limit' => 2, 'null' => true])
                  ->addColumn('ranking_category_3_decode', 'string', ['limit' => 80, 'null' => true])
                  ->addColumn('message', 'binary', ['limit' => 12])
                  ->addColumn('message_decode', 'text', ['null' => true])
                  ->addColumn('news_binary', 'blob')
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->create();
        }

        // bxt_ranking
        if (!$this->hasTable('bxt_ranking')) {
            $table = $this->table('bxt_ranking', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11])
                  ->addColumn('game_region', 'char', ['limit' => 1])
                  ->addColumn('news_id', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('category_id', 'integer', ['signed' => false, 'limit' => 2])
                  ->addColumn('category_id_decode', 'string', ['limit' => 80, 'null' => true])
                  ->addColumn('score', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('trainer_id', 'integer', ['signed' => false, 'limit' => 5])
                  ->addColumn('secret_id', 'integer', ['signed' => false, 'limit' => 5])
                  ->addColumn('player_name', 'binary', ['limit' => 7])
                  ->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true])
                  ->addColumn('player_gender', 'integer', ['signed' => false, 'limit' => 1])
                  ->addColumn('player_gender_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('player_age', 'integer', ['signed' => false, 'limit' => 3])
                  ->addColumn('player_region', 'integer', ['signed' => false, 'limit' => 3])
                  ->addColumn('player_region_decode', 'string', ['limit' => 64, 'null' => true])
                  ->addColumn('player_zip', 'binary', ['limit' => 3])
                  ->addColumn('player_zip_decode', 'string', ['limit' => 16, 'null' => true])
                  ->addColumn('player_message', 'binary', ['limit' => 12])
                  ->addColumn('player_message_decode', 'text', ['null' => true])
                  ->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11])
                  ->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->create();
        }

        /**
         * 2) UPDATE-IF-EXISTS BRANCHES
         *    These are additive / idempotent and ensure any missing fields from tables.sql are present.
         */

        // bxt_battle_tower_records
        if ($this->hasTable('bxt_battle_tower_records')) {
            $table = $this->table('bxt_battle_tower_records');

            if (!$table->hasColumn('game_region')) {
                $options = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $options['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $options);
            }

            // Decode columns
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

            // Account / timestamp
            if (!$table->hasColumn('account_id')) {
                $table->addColumn('account_id', 'integer', ['signed' => false, 'limit' => 11, 'null' => false, 'after' => 'num_fainted_pokemon']);
            }
            if (!$table->hasColumn('timestamp')) {
                $after = $table->hasColumn('account_id') ? 'account_id' : null;
                $opts  = ['default' => 'CURRENT_TIMESTAMP'];
                if ($after !== null) {
                    $opts['after'] = $after;
                }
                $table->addColumn('timestamp', 'timestamp', $opts);
            }

            // Index for rate limiting
            if (!$table->hasIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'])) {
                $table->addIndex(['account_id', 'trainer_id', 'secret_id', 'timestamp'], ['name' => 'idx_limit_24h']);
            }

            // Remove old email column if present
            if ($table->hasColumn('email')) {
                $table->removeColumn('email');
            }

            $table->save();
        }

        // bxt_battle_tower_trainers
        if ($this->hasTable('bxt_battle_tower_trainers')) {
            $table = $this->table('bxt_battle_tower_trainers');

            if (!$table->hasColumn('game_region')) {
                $opts = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $opts);
            }

            // Decode columns
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
            if (!$table->hasColumn('timestamp')) {
                $after = $table->hasColumn('account_id') ? 'account_id' : null;
                $opts  = ['default' => 'CURRENT_TIMESTAMP'];
                if ($after !== null) {
                    $opts['after'] = $after;
                }
                $table->addColumn('timestamp', 'timestamp', $opts);
            }

            $table->save();
        }

        // bxt_battle_tower_leaders
        if ($this->hasTable('bxt_battle_tower_leaders')) {
            $table = $this->table('bxt_battle_tower_leaders');

            if (!$table->hasColumn('game_region')) {
                $opts = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $opts);
            }

            if (!$table->hasColumn('player_name_decode')) {
                $table->addColumn('player_name_decode', 'string', ['limit' => 32, 'null' => true, 'after' => 'player_name']);
            }
            if (!$table->hasColumn('level_decode')) {
                $table->addColumn('level_decode', 'string', ['limit' => 16, 'null' => true, 'after' => 'level']);
            }
            if (!$table->hasColumn('timestamp')) {
                $after = $table->hasColumn('level_decode') ? 'level_decode' : 'level';
                $table->addColumn('timestamp', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'after' => $after]);
            }

            $table->save();
        }

        // bxt_exchange
        if ($this->hasTable('bxt_exchange')) {
            $table = $this->table('bxt_exchange');

            // Rename legacy columns from InitialSchema if present
            if ($table->hasColumn('entry_time') && !$table->hasColumn('timestamp')) {
                $table->renameColumn('entry_time', 'timestamp');
            }
            if ($table->hasColumn('trainer_name') && !$table->hasColumn('player_name')) {
                $table->renameColumn('trainer_name', 'player_name');
            }

            // Ensure core columns present
            if (!$table->hasColumn('id')) {
                /**
                 * MySQL/MariaDB requirement:
                 * AUTO_INCREMENT columns must be part of an index/key.
                 *
                 * The consolidated schema (from 20251117175526_consolidate_regional_tables.php)
                 * produces bxt_exchange without an id column, but with a UNIQUE(account_id, trainer_id, secret_id).
                 *
                 * If we add an AUTO_INCREMENT id column without also making it a key, MySQL/MariaDB will error:
                 *   "Incorrect table definition; there can be only one auto column and it must be defined as a key"
                 *
                 * Do it in a single ALTER TABLE that both adds the column and adds a UNIQUE key on it.
                 */
                $countRow = $this->fetchRow('SELECT COUNT(*) AS cnt FROM bxt_exchange');
                $cnt = 0;
                if (is_array($countRow) && $countRow) {
                    // Some PDO drivers return both numeric + associative keys; be defensive.
                    if (array_key_exists('cnt', $countRow)) {
                        $cnt = (int)$countRow['cnt'];
                    } elseif (array_key_exists('CNT', $countRow)) {
                        $cnt = (int)$countRow['CNT'];
                    } else {
                        $vals = array_values($countRow);
                        $cnt = isset($vals[0]) ? (int)$vals[0] : 0;
                    }
                }

                if ($cnt === 0) {
                    // Empty table: safe to add AUTO_INCREMENT + key in one shot.
                    $this->execute("
                        ALTER TABLE bxt_exchange
                            ADD COLUMN id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
                            ADD UNIQUE KEY idx_bxt_exchange_id (id)
                    ");
                } else {
                    // Non-empty table: add as nullable, backfill deterministic ids, then promote to AUTO_INCREMENT.
                    $this->execute("ALTER TABLE bxt_exchange ADD COLUMN id INT(11) UNSIGNED NULL FIRST");
                    $this->execute("SET @__bxt_exchange_id := 0");
                    $orderCols = [];
                    foreach (['account_id', 'trainer_id', 'secret_id', 'timestamp', 'email'] as $col) {
                        if ($table->hasColumn($col)) {
                            $orderCols[] = $col;
                        }
                    }
                    $orderBy = $orderCols ? (' ORDER BY ' . implode(', ', $orderCols)) : '';

                    $sql = "
                        UPDATE bxt_exchange
                           SET id = (@__bxt_exchange_id := @__bxt_exchange_id + 1)
                    ";
                    $sql .= $orderBy;
                    $this->execute($sql);
                    $this->execute("ALTER TABLE bxt_exchange MODIFY COLUMN id INT(11) UNSIGNED NOT NULL");
                    $this->execute("ALTER TABLE bxt_exchange ADD UNIQUE KEY idx_bxt_exchange_id (id)");
                    $this->execute("ALTER TABLE bxt_exchange MODIFY COLUMN id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }

                // Refresh table metadata for subsequent hasColumn/hasIndex checks
                $table = $this->table('bxt_exchange');
}

            if (!$table->hasColumn('game_region')) {
                $opts = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $opts);
            }

            if (!$table->hasColumn('timestamp')) {
                $after = null;
                if ($table->hasColumn('email')) {
                    $after = 'email';
                } elseif ($table->hasColumn('account_id')) {
                    $after = 'account_id';
                } elseif ($table->hasColumn('mail_decode')) {
                    $after = 'mail_decode';
                }
                $opts = ['default' => 'CURRENT_TIMESTAMP'];
                if ($after !== null) {
                    $opts['after'] = $after;
                }
                $table->addColumn('timestamp', 'timestamp', $opts);
            }

            if (!$table->hasColumn('account_id')) {
                // Place before email if possible
                $opts = ['signed' => false, 'limit' => 11, 'null' => false];
                if ($table->hasColumn('game_region')) {
                    $opts['after'] = 'game_region';
                }
                $table->addColumn('account_id', 'integer', $opts);
            }

            if (!$table->hasColumn('email')) {
                $opts = ['limit' => 30, 'null' => false, 'comment' => 'DION email'];
                if ($table->hasColumn('account_id')) {
                    $opts['after'] = 'account_id';
                }
                $table->addColumn('email', 'string', $opts);
            }

            // Decode columns
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

            // Unique index
            if (!$table->hasIndex(['account_id', 'trainer_id', 'secret_id'])) {
                $table->addIndex(['account_id', 'trainer_id', 'secret_id'], ['unique' => true, 'name' => 'UNIQUE']);
            }

            $table->save();
        }

        // bxt_news
        if ($this->hasTable('bxt_news')) {
            $table = $this->table('bxt_news');

            if (!$table->hasColumn('game_region')) {
                // Some older schemas had per-region columns; we just add game_region where possible.
                $opts = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $opts);
            }

            // Decode columns (as before)
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
                // For older multi-language schemas this will be after a generic `message` if present.
                $after = $table->hasColumn('message') ? 'message' : null;
                $opts  = ['null' => true];
                if ($after !== null) {
                    $opts['after'] = $after;
                }
                $table->addColumn('message_decode', 'text', $opts);
            }

            $table->save();
        }

        // bxt_ranking
        if ($this->hasTable('bxt_ranking')) {
            $table = $this->table('bxt_ranking');

            if (!$table->hasColumn('game_region')) {
                $opts = ['limit' => 1, 'null' => false];
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }
                $table->addColumn('game_region', 'char', $opts);
            }

            // Decode columns (as before)
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

        /**
         * 3) RANKING CATEGORY NAME UPDATES
         *
         * NOTE:
         * - The original migration used multiple UPDATE statements inside one execute() call.
         * - On many PDO/MySQL setups, multi-statement execution is disabled, causing this migration to fail.
         * - This replaces that block with a single UPDATE + CASE.
         */
        if ($this->hasTable('bxt_ranking_categories')) {
            $this->execute("
                UPDATE bxt_ranking_categories
                   SET name = CASE id
                           WHEN 0 THEN 'LAST HOF RECORD'
                           WHEN 1 THEN 'LAST HOF STEPS'
                           WHEN 2 THEN 'LAST HOF HEALED'
                           WHEN 3 THEN 'LAST HOF BATTLES'
                           WHEN 4 THEN 'STEPS WALKED'
                           WHEN 5 THEN 'BATTLE TOWER WINS'
                           WHEN 6 THEN 'TMs/HMs TAUGHT'
                           WHEN 7 THEN 'POKéMON BATTLES'
                           WHEN 8 THEN 'POKéMON ENCOUNTER'
                           WHEN 9 THEN 'TRAINER BATTLES'
                           WHEN 10 THEN 'UNUSED'
                           WHEN 11 THEN 'HOF ENTRIES'
                           WHEN 12 THEN 'POKéMON CAUGHT'
                           WHEN 13 THEN 'POKéMON HOOKED'
                           WHEN 14 THEN 'EGGS HATCHED'
                           WHEN 15 THEN 'POKéMON EVOLVED'
                           WHEN 16 THEN 'FRUIT PICKED'
                           WHEN 17 THEN 'PARTY HEALED'
                           WHEN 18 THEN 'MYSTERY GIFT USED'
                           WHEN 19 THEN 'TRADES COMPLETED'
                           WHEN 20 THEN 'FLY USED'
                           WHEN 21 THEN 'SURF USED'
                           WHEN 22 THEN 'WATERFALL USED'
                           WHEN 23 THEN 'TIMES WHITED OUT'
                           WHEN 24 THEN 'LUCKY NUMBER WINS'
                           WHEN 25 THEN 'TOTAL PHONE CALLS'
                           WHEN 26 THEN 'UNUSED'
                           WHEN 27 THEN 'COLOSSEUM BATTLES'
                           WHEN 28 THEN 'SPLASH USED'
                           WHEN 29 THEN 'HEADBUTT USED'
                           WHEN 30 THEN 'UNUSED'
                           WHEN 31 THEN 'COLOSSEUM WINS'
                           WHEN 32 THEN 'COLOSSEUM LOSSES'
                           WHEN 33 THEN 'COLOSSEUM DRAWS'
                           WHEN 34 THEN 'SELF-KO MOVE USED'
                           WHEN 35 THEN 'SLOT WIN STREAK'
                           WHEN 36 THEN 'BEST SLOT STREAK'
                           WHEN 37 THEN 'SLOT COINS WON'
                           WHEN 38 THEN 'TOTAL MONEY'
                           WHEN 39 THEN 'LARGEST MAGIKARP'
                           WHEN 40 THEN 'SMALLEST MAGIKARP'
                           WHEN 41 THEN 'BUG CONTEST SCORE'
                        ELSE name
                    END
                 WHERE id BETWEEN 0 AND 41
            ");
        }

        /**
         * 4) TRIGGERS FOR RATE LIMITING
         */
        try {
            // 5 per 24 hours limit for battle tower records
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
    }
}
