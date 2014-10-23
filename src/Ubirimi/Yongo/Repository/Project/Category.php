<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class Category
{
    public $name;
    public $description;
    public $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;
    }

    public function deleteById($clientId, $projectCategoryId) {
        $query = "delete from project_category where id = ? and client_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectCategoryId, $clientId);
        $stmt->execute();

        $query = "update project set project_category_id = null where project_category_id = ? and client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectCategoryId, $clientId);
        $stmt->execute();
    }

    public function save($currentDate) {
        $query = "INSERT INTO project_category(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getAll($clientId) {
        $query = "SELECT * from project_category WHERE client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($categoryId) {
        $query = "SELECT * from project_category WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateById($categoryId, $name, $description, $date) {
        $query = "update project_category set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("sssi", $name, $description, $date, $categoryId);
        $stmt->execute();
    }

    public function getByName($name, $projectCategoryId, $clientId) {
        $query = 'select id, name from project_category where client_id = ? and LOWER(name) = LOWER(?) ';
        if ($projectCategoryId)
            $query .= 'and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($projectCategoryId)
            $stmt->bind_param("isi", $clientId, $name, $projectCategoryId);
        else
            $stmt->bind_param("is", $clientId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }
}
