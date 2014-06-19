<?php
namespace Ubirimi\Yongo\Repository\Issue;
use Ubirimi\Container\UbirimiContainer;

class IssueTypeScreenScheme {

    private $name;
    private $description;
    private $clientId;
    private $currentDate;

    function __construct($clientId, $name, $description) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getByClientId($clientId) {
        $query = "select issue_type_screen_scheme.id, issue_type_screen_scheme.name, issue_type_screen_scheme.description " .
            "from issue_type_screen_scheme " .
            "where issue_type_screen_scheme.client_id = ?";

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

    public static function getMetaDataById($Id) {
        $query = "select * " .
            "from issue_type_screen_scheme " .
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

    public static function getDataById($Id) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.screen_scheme_id, issue_type_screen_scheme_data.issue_type_id, " .
                    "issue_type.name as issue_type_name, screen_scheme.name as screen_scheme_name, issue_type_screen_scheme_data.issue_type_screen_scheme_id " .
                 "from issue_type_screen_scheme_data " .
                 "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
                 "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
                 "where issue_type_screen_scheme_data.id = ? ";

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

    public static function getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.issue_type_id, issue_type_screen_scheme_data.screen_scheme_id, " .
                    "screen_scheme.name as screen_scheme_name, issue_type.name as issue_type_name " .
                 "from issue_type_screen_scheme_data " .
                 "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
                 "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
                 "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueTypeScreenSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getDataByIssueTypeScreenSchemeIdAndIssueTypeId($issueTypeScreenSchemeId, $issueTypeId) {
        $query = "select issue_type_screen_scheme_data.id, issue_type_screen_scheme_data.issue_type_id, issue_type_screen_scheme_data.screen_scheme_id, " .
            "screen_scheme.name as screen_scheme_name, issue_type.name as issue_type_name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
            "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? and " .
                "issue_type_screen_scheme_data.issue_type_id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueTypeScreenSchemeId, $issueTypeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update issue_type_screen_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("sssi", $name, $description, $date, $Id);
            $stmt->execute();
        }
    }

    public static function deleteDataById($Id) {
        $query = "delete from issue_type_screen_scheme_data where id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
        }
    }

    public static function deleteDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId) {
        $query = "delete from issue_type_screen_scheme_data where issue_type_screen_scheme_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueTypeScreenSchemeId);
            $stmt->execute();
        }
    }

    public static function addData($screenSchemeId, $issueTypeId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $screenSchemeId, $issueTypeId, $currentDate);
            $stmt->execute();
        }
    }

    public static function addDataComplete($issueTypeScreenSchemeId, $issueTypeId, $screenSchemeId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, screen_scheme_id, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("isis", $issueTypeScreenSchemeId, $issueTypeId, $screenSchemeId, $currentDate);
            $stmt->execute();
        }
    }

    public static function updateDataById($screenSchemeId, $issueTypeId, $issueTypeScreenSchemeId) {
        $query = "update issue_type_screen_scheme_data set screen_scheme_id = ? where issue_type_screen_scheme_id = ? and issue_type_id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iii", $screenSchemeId, $issueTypeScreenSchemeId, $issueTypeId);
            $stmt->execute();
        }
    }

    public static function getScreenSchemes($issueTypeScreenSchemeId) {
        $query = "select screen_scheme.id, screen_scheme.name " .
            "from issue_type_screen_scheme_data " .
            "left join screen_scheme on screen_scheme.id = issue_type_screen_scheme_data.screen_scheme_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? " .
            "group by screen_scheme.id";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueTypeScreenSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getIssueTypesForScreenScheme($issueTypeScreenSchemeId, $screenSchemeId) {
        $query = "select issue_type.id, issue_type.name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type on issue_type.id = issue_type_screen_scheme_data.issue_type_id " .
            "where issue_type_screen_scheme_data.issue_type_screen_scheme_id = ? and screen_scheme_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueTypeScreenSchemeId, $screenSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteById($issueTypeScreenSchemeId) {
        $query = "delete from issue_type_screen_scheme where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueTypeScreenSchemeId);
            $stmt->execute();
        }
    }

    public static function deleteByClientId($clientId) {
        $issueTypeScreenSchemes = IssueTypeScreenScheme::getByClientId($clientId);
        while ($issueTypeScreenSchemes && $issueTypeScreenScheme = $issueTypeScreenSchemes->fetch_array(MYSQLI_ASSOC)) {
            IssueTypeScreenScheme::deleteDataByIssueTypeScreenSchemeId($issueTypeScreenScheme['id']);
            IssueTypeScreenScheme::deleteById($issueTypeScreenScheme['id']);
        }
    }

    public static function getByScreenSchemeId($screenSchemeId) {
        $query = "select issue_type_screen_scheme.id, issue_type_screen_scheme.name " .
            "from issue_type_screen_scheme_data " .
            "left join issue_type_screen_scheme on issue_type_screen_scheme.id = issue_type_screen_scheme_data.issue_type_screen_scheme_id " .
            "where issue_type_screen_scheme_data.screen_scheme_id = ? " .
            "group by issue_type_screen_scheme_data.screen_scheme_id";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $screenSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from issue_type_screen_scheme where client_id = ? and LOWER(name) = ?";
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