<?php

use Phinx\Migration\AbstractMigration;

class CompanyNameClientNull extends AbstractMigration
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
        $this->execute("ALTER TABLE  `client` CHANGE  `company_name`  `company_name` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}