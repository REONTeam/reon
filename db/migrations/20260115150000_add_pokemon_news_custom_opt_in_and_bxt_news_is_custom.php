<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Adds Pokémon News custom/vanilla tracking flags.
 *
 * sys_users.custom_pokemon_news_opt_in
 *   - Per-account opt-in (default OFF).
 *
 * bxt_news.is_custom
 *   - Per-news row flag (0 = vanilla, 1 = custom).
 */
final class AddPokemonNewsCustomOptInAndBxtNewsIsCustom extends AbstractMigration
{
    public function change(): void
    {
        // sys_users.custom_pokemon_news_opt_in
        if ($this->hasTable('sys_users')) {
            $table = $this->table('sys_users');

            if (!$table->hasColumn('custom_pokemon_news_opt_in')) {
                $opts = [
                    'null'    => false,
                    'default' => 0,
                ];

                // Prefer placing it immediately after the existing trade corner region allowlist.
                if ($table->hasColumn('trade_region_allowlist')) {
                    $opts['after'] = 'trade_region_allowlist';
                } elseif ($table->hasColumn('money_spent')) {
                    $opts['after'] = 'money_spent';
                }

                $table->addColumn('custom_pokemon_news_opt_in', 'boolean', $opts);
                $table->save();
            }
        }

        // bxt_news.is_custom
        if ($this->hasTable('bxt_news')) {
            $table = $this->table('bxt_news');

            $changed = false;

            if (!$table->hasColumn('is_custom')) {
                $opts = [
                    'null'    => false,
                    'default' => 0,
                ];

                // Place is_custom immediately after id, i.e. directly to the left of game_region.
                if ($table->hasColumn('id')) {
                    $opts['after'] = 'id';
                }

                $table->addColumn('is_custom', 'boolean', $opts);
                $changed = true;
            }

            // Index for per-region, per-track latest row lookups.
            if (!$table->hasIndex(['game_region', 'is_custom'])) {
                $table->addIndex(['game_region', 'is_custom'], ['name' => 'idx_bxt_news_region_custom']);
                $changed = true;
            }

            if ($changed) {
                $table->save();
            }
        }
    }
}
