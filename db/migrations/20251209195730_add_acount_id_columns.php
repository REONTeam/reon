<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAcountIdColumns extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Mario Kart Advance (AMKJ) - time trials
        $table = $this->table('amkj_ghosts');
        $table->addColumn('acc_id', 'integer', [
            'limit' => 11,
            'after' => 'id'
        ]);
        $table->removeColumn('unk18');
        $table->removeColumn('course');
        $table->renameColumn('course_no', 'course');
        $table->update();

        // Mario Kart Advance (AMKJ) - mobile GP
        $table = $this->table('amkj_ghosts_mobilegp');
        $table->addColumn('acc_id', 'integer', [
            'limit' => 11,
            'after' => 'id'
        ]);
        $table->removeColumn('course_no');
        $table->removeColumn('unk18');
        $table->removeColumn('course');
        $table->update();

        // EX Monopoly (AMOJ)
        $table = $this->table('amoj_ranking');
        $table->addColumn('acc_id', 'integer', [
            'limit' => 11,
            'after' => 'id'
        ]);
        $table->update();

        // Exciting Bass (AMGJ)
        $table = $this->table('amgj_rankings');
        $table->addColumn('acc_id', 'integer', [
            'limit' => 11,
            'after' => 'id'
        ]);
        $table->update();

        // Yu-Gi-Oh! Duel Monsters 5 Expert I (AY5J)
        $table = $this->table('ay5j_rankings');
        $table->addColumn('acc_id', 'integer', [
            'limit' => 11,
            'after' => 'id'
        ]);
        $table->update();
    }
}
