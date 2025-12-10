<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAmcjBottleMail extends AbstractMigration
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
        if (!$this->hasTable('amcj_trades')) {
            $table = $this->table('amcj_trades', ['id' => false, 'primary_key' => 'id']);
            $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11, 'null' => false])
                  ->addColumn('acc_id', 'integer', ['limit' => 11, 'null' => false])
                  ->addColumn('email', 'string', ['limit' => 24, 'null' => false])
                  ->addColumn('message', 'string', ['limit' => 698, 'null' => false])
                  ->create();
        }
    }
}
