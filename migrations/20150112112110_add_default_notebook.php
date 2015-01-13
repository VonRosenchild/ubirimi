<?php

use Phinx\Migration\AbstractMigration;
use Ubirimi\Util;

class AddDefaultNotebook extends AbstractMigration
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

        // add for each user the default notebook
        $rows = $this->fetchAll('SELECT * FROM user');
        foreach ($rows as $row) {
            $query = sprintf("insert into qn_notebook(user_id, name, description, default_flag, date_created) values (%s, '%s', '%s', 1, '%s')", $row['id'], 'My Default Notebook', 'My Default Notebook', $date);
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