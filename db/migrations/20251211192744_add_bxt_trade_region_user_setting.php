<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBxtTradeRegionUserSetting extends AbstractMigration
{
    /**
     * Add BXT Trade Region User Setting
     *
     * Adds a column that contains a string for the list of regions each
     * user would be open to trading with in the Crystal Trade Corner.
     * Defaults to including every single possible region.
     */
    public function change(): void
    {
        $table = $this->table("sys_users");
        $table->addColumn("trade_region_whitelist","string",["limit" => 8,"null" => false,"default" => "efdsipuj"])
              ->update();
    }
}
