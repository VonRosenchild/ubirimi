<?php

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class Entity
{
    public $tableName;

    public function getById($Id) {

    }

    public function deleteById($Id) {
        $query = 'delete from ' . $this->tableName . ' where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }
}
