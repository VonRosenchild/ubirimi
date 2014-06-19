<?php

namespace Ubirimi\Yongo\Repository\Workflow;
use Ubirimi\Container\UbirimiContainer;

class WorkflowScheme {

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
        $query = "INSERT INTO workflow_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function addData($workflowSchemeId, $workflowId, $currentDate) {
        $query = "INSERT INTO workflow_scheme_data(workflow_scheme_id, workflow_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $workflowSchemeId, $workflowId, $currentDate);
            $stmt->execute();
        }
    }

    public static function deleteDataByWorkflowSchemeId($Id) {
        $query = "delete from workflow_scheme_data where workflow_scheme_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
        }
    }

    public static function deleteById($Id) {
        $query = "delete from workflow_scheme where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
        }
    }

    public static function updateMetaDataById($Id, $name, $description) {
        $query = "update workflow_scheme set name = ?, description = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ssi", $name, $description, $Id);
            $stmt->execute();
        }
    }

    public static function getDataById($Id) {
        $query = "select workflow_scheme_data.id, workflow_scheme_data.workflow_id, workflow.name, workflow.description, " .
                 "workflow_scheme_data.workflow_scheme_id " .
                 "from workflow_scheme_data " .
                 "left join workflow on workflow.id = workflow_scheme_data.workflow_id " .
                 "where workflow_scheme_id = ? ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getMetaDataById($Id) {
        $query = "select * " .
            "from workflow_scheme " .
            "where id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getMetaDataByClientId($clientId) {
        $query = "select * from workflow_scheme where client_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getWorkflows($workflowSchemeId) {
        $query = "select workflow.* " .
            "from workflow_scheme " .
            "left join workflow_scheme_data on workflow_scheme_data.workflow_scheme_id = workflow_scheme.id " .
            "left join workflow on workflow.id = workflow_scheme_data.workflow_id " .
            "where workflow_scheme.id = ? ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $workflowSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getByWorkflowId($workflowId) {
        $query = "select workflow_scheme.id, workflow_scheme.name " .
            "from workflow_scheme_data " .
            "left join workflow_scheme on workflow_scheme.id = workflow_scheme_data.workflow_scheme_id " .
            "where workflow_scheme_data.workflow_id = ? " .
            "group by workflow_scheme_data.workflow_scheme_id";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $workflowId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteByClientId($clientId) {
        $schemes = WorkflowScheme::getMetaDataByClientId($clientId);
        if ($schemes) {
            while ($scheme = $schemes->fetch_array(MYSQLI_ASSOC)) {
                WorkflowScheme::deleteDataByWorkflowSchemeId($scheme['id']);
                WorkflowScheme::deleteById($scheme['id']);
            }
        }
    }

    public static function getByClientIdAndName($clientId, $name) {
        $query = "select * from workflow_scheme where client_id = ? and LOWER(name) = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("is", $clientId, $name);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }
}