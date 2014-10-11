<?php

namespace Ubirimi\Yongo\Repository\Screen;

use Ubirimi\Container\UbirimiContainer;

class Scheme
{
    public $name;
    public $description;
    public $clientId;

    function __construct($clientId, $name, $description) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function addData($screenSchemeId, $operationId, $screenId, $currentDate) {
        $query = "INSERT INTO screen_scheme_data(screen_scheme_id, sys_operation_id, screen_id, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $screenSchemeId, $operationId, $screenId, $currentDate);
        $stmt->execute();
    }

    public static function deleteDataByScreenSchemeId($Id) {
        $query = "delete from screen_scheme_data where screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public static function updateDataById($screenSchemeId, $operationId, $screenId) {
        $query = "update screen_scheme_data set screen_id = ? where screen_scheme_id = ? and sys_operation_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $screenId, $screenSchemeId, $operationId);
        $stmt->execute();
    }

    public static function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update screen_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public static function getDataByScreenSchemeId($screenSchemeId) {
        $query = "select screen_scheme_data.id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.screen_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getDataByScreenSchemeIdAndSysOperationId($screenSchemeId, $sysOperationId) {
        $query = "select screen_scheme_data.id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.screen_scheme_id = ? and " .
                "screen_scheme_data.sys_operation_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $screenSchemeId, $sysOperationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function getDataByScreenDataId($Id) {
        $query = "select screen_scheme_data.id, screen_scheme_data.screen_scheme_id, screen_scheme_data.sys_operation_id, screen_scheme_data.screen_id, screen.name as screen_name, sys_operation.name as operation_name " .
            "from screen_scheme_data " .
            "left join screen on screen.id = screen_scheme_data.screen_id " .
            "left join sys_operation on sys_operation.id = screen_scheme_data.sys_operation_id " .
            "where screen_scheme_data.id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function getMetaDataById($Id) {
        $query = "select * " .
            "from screen_scheme " .
            "where id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function getMetaDataByClientId($clientId) {
        $query = "select * from screen_scheme where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from screen_scheme where client_id = ? and LOWER(name) = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getByIssueType($issueTypeId, $clientId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
                 "from screen_scheme " .
                 "left join issue_type_screen_scheme_data on issue_type_screen_scheme_data.screen_scheme_id = screen_scheme.id " .
                 "where screen_scheme.client_id = ? " .
                 "and issue_type_screen_scheme_data.issue_type_id = ? " .
                 "group by screen_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getByScreenId($clientId, $screenId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
            "from screen_scheme_data " .
            "left join screen_scheme on screen_scheme.id = screen_scheme_data.screen_scheme_id " .
            "where screen_scheme.client_id = ? " .
            "and screen_scheme_data.screen_id = ? " .
            "group by screen_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $screenId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function deleteById($screenSchemeId) {
        $query = "delete from screen_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $screenSchemeId);
        $stmt->execute();
    }

    public static function deleteByClientId($clientId) {
        $screenSchemes = Scheme::getMetaDataByClientId($clientId);
        while ($screenSchemes && $screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)) {
            Scheme::deleteDataByScreenSchemeId($screenScheme['id']);
            Scheme::deleteById($screenScheme['id']);
        }
    }
}
