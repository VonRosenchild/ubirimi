<?php

use Phinx\Migration\AbstractMigration;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class AddQuickNotes extends AbstractMigration
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
        $date = Util::getServerCurrentDateTime();
        $rows = $this->fetchAll('SELECT * FROM client');
        foreach ($rows as $row) {
            $query = sprintf("insert into client_product(client_id, sys_product_id, date_created) values (%s, %s, '%s')", $row['id'], SystemProduct::SYS_PRODUCT_QUICK_NOTES, $date);
            $this->execute($query);
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}