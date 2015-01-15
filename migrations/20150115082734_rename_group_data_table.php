<?php

use Phinx\Migration\AbstractMigration;

class RenameGroupDataTable extends AbstractMigration
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
        $this->execute('RENAME TABLE `ubirimi`.`group_data` TO `ubirimi`.`general_group_data`;');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}