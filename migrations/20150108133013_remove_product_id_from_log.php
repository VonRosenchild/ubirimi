<?php

use Phinx\Migration\AbstractMigration;

class RemoveProductIdFromLog extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('ALTER TABLE `general_log` DROP `sys_product_id`;');

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}