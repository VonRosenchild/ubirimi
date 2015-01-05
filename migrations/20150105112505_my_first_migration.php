<?php

use Phinx\Migration\AbstractMigration;

class MyFirstMigration extends AbstractMigration
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
        $this->execute('ALTER TABLE  `yongo_issue` CHANGE  `original_estimate`  `original_estimate` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                        CHANGE  `remaining_estimate`  `remaining_estimate` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}