<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBxtExchangeLog extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('bxt_exchange_log')) {
            return;
        }

        $table = $this->table('bxt_exchange_log', [
            'id' => false,
        ]);

        // Player 1
        $table
            ->addColumn('account_id_1', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 4, // int(11)
            ])
            ->addColumn('email_1', 'string', [
                'null' => false,
                'limit' => 30,
                'comment' => 'DION email',
            ])
            ->addColumn('game_region_1', 'char', [
                'null' => false,
                'limit' => 1,
            ])
            ->addColumn('trainer_id_1', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 2, // smallint
                'comment' => 'Trainer ID',
            ])
            ->addColumn('secret_id_1', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 2, // smallint
                'comment' => 'Secret ID',
            ])
            ->addColumn('player_name_1', 'binary', [
                'null' => false,
                'limit' => 7,
                'comment' => 'Name of player',
            ])
            ->addColumn('player_name_decode_1', 'string', [
                'null' => true,
                'limit' => 32,
                'default' => null,
            ])
            ->addColumn('gender_1', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 1, // tinyint
                'comment' => 'Gender of Pokémon',
            ])
            ->addColumn('gender_decode_1', 'string', [
                'null' => true,
                'limit' => 16,
                'default' => null,
            ])
            ->addColumn('species_1', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 1, // tinyint
                'comment' => 'Decimal Pokémon ID.',
            ])
            ->addColumn('species_decode_1', 'string', [
                'null' => true,
                'limit' => 32,
                'default' => null,
            ])
            ->addColumn('pokemon_1', 'binary', [
                'null' => false,
                'limit' => 65,
                'comment' => 'Pokémon',
            ])
            ->addColumn('pokemon_decode_1', 'text', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('mail_1', 'binary', [
                'null' => false,
                'limit' => 47,
                'comment' => 'Held mail of Pokémon',
            ])
            ->addColumn('mail_decode_1', 'text', [
                'null' => true,
                'default' => null,
            ]);

        // Player 2
        $table
            ->addColumn('account_id_2', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 4, // int(11)
            ])
            ->addColumn('email_2', 'string', [
                'null' => false,
                'limit' => 30,
                'comment' => 'DION email',
            ])
            ->addColumn('game_region_2', 'char', [
                'null' => false,
                'limit' => 1,
            ])
            ->addColumn('trainer_id_2', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 2, // smallint
                'comment' => 'Trainer ID',
            ])
            ->addColumn('secret_id_2', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 2, // smallint
                'comment' => 'Secret ID',
            ])
            ->addColumn('player_name_2', 'binary', [
                'null' => false,
                'limit' => 7,
                'comment' => 'Name of player',
            ])
            ->addColumn('player_name_decode_2', 'string', [
                'null' => true,
                'limit' => 32,
                'default' => null,
            ])
            ->addColumn('gender_2', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 1, // tinyint
                'comment' => 'Gender of Pokémon',
            ])
            ->addColumn('gender_decode_2', 'string', [
                'null' => true,
                'limit' => 16,
                'default' => null,
            ])
            ->addColumn('species_2', 'integer', [
                'null' => false,
                'signed' => false,
                'limit' => 1, // tinyint
                'comment' => 'Decimal Pokémon ID.',
            ])
            ->addColumn('species_decode_2', 'string', [
                'null' => true,
                'limit' => 32,
                'default' => null,
            ])
            ->addColumn('pokemon_2', 'binary', [
                'null' => false,
                'limit' => 65,
                'comment' => 'Pokémon',
            ])
            ->addColumn('pokemon_decode_2', 'text', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('mail_2', 'binary', [
                'null' => false,
                'limit' => 47,
                'comment' => 'Held mail of Pokémon',
            ])
            ->addColumn('mail_decode_2', 'text', [
                'null' => true,
                'default' => null,
            ]);

        // Common
        $table
            ->addColumn('timestamp', 'timestamp', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('bxt_exchange_log')) {
            $this->table('bxt_exchange_log')->drop()->save();
        }
    }
}
