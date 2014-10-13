<?php

namespace Ubirimi\Repository\General;

use Ubirimi\Agile\Repository\Board;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\User\User;
use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\Configuration;
use Ubirimi\Yongo\Repository\Field\ConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\Event;
use Ubirimi\Yongo\Repository\Issue\LinkType;
use Ubirimi\Yongo\Repository\Issue\SecurityScheme;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Issue\Type;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Notification\Scheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\Condition;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;
use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Request as PaymillRequest;

class Client
{
    const INSTANCE_TYPE_ON_DEMAND = 1;
    const INSTANCE_TYPE_DOWNLOAD = 2;

    public function getClientIdAnonymous() {
        $httpHOST = Util::getHttpHost();
        return UbirimiContainer::get()['repository']->get('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
    }

    public function deleteGroups($clientId) {
        $groups = $this->getRepository('ubirimi.user.group')->getByClientId($clientId);
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get('ubirimi.user.group')->deleteByIdForYongo($group['id']);

            UbirimiContainer::get()['repository']->get('ubirimi.user.group')->deleteByIdForDocumentator($group['id']);
        }
    }

    public function addProduct($clientId, $productId, $date) {
        $query = "INSERT INTO client_product(client_id, sys_product_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $clientId, $productId, $date);
        $stmt->execute();
    }

    public function deleteProduct($clientId, $productId) {
        $query = "delete from client_product where client_id = ? and sys_product_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
    }

    public function getByBaseURL($httpHOST, $resultType = null, $resultColumn = null) {
        $query = 'SELECT * ' .
            'FROM client ' .
            "WHERE client.base_url = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $httpHOST);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        return $data[$resultColumn];
                    else
                        return $data;
                }
            } else
                return $result;
        }
    }

    public function getYongoSetting($clientId, $settingName) {
        $query = 'SELECT ' . $settingName . ' from client_yongo_settings where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            return $data[$settingName];
        } else
            return null;
    }

    public function getLastMonthActiveClients() {
        $date = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
        $query = 'SELECT count(general_log.id) as log_entries, client.id, client.company_domain, client.company_name, client.contact_email, client.date_created ' .
                 'FROM general_log ' .
                 'LEFT JOIN client ON client.id = general_log.client_id ' .
                 'WHERE general_log.date_created >= ? AND ' .
                    'client.id IS NOT NULL ' .
                 'GROUP BY general_log.client_id ' .
                 'HAVING log_entries > 10 ' .
                 'ORDER BY log_entries DESC';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getSettingsByBaseURL($url) {
        $query = 'SELECT client.id, client_settings.operating_mode ' .
            'FROM client ' .
            'LEFT JOIN client_settings on client_settings.client_id = client.id ' .
            "WHERE client.base_url = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $url);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function createDefaultYongoSettings($clientId) {
        $query = "INSERT INTO client_yongo_settings(client_id, allow_unassigned_issues_flag, issues_per_page, issue_linking_flag, time_tracking_flag,
                  time_tracking_hours_per_day, time_tracking_days_per_week, time_tracking_default_unit)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $allowUnassignedIssuesFlag = 0;
        $issuesPerPage = 50;
        $issueLinkingFlag = 1;
        $timeTrackingFlag = 1;
        $timeTrackingHoursPerDay = 8;
        $timeTrackingDaysPerWeek = 5;
        $timeTrackingDefaultUnit = 'm';

        $stmt->bind_param("iiiiiiis", $clientId, $allowUnassignedIssuesFlag, $issuesPerPage, $issueLinkingFlag, $timeTrackingFlag, $timeTrackingHoursPerDay, $timeTrackingDaysPerWeek, $timeTrackingDefaultUnit);

        $stmt->execute();
    }

    public function setInstalledFlag($clientId, $installedFlag) {
        $query = "update client set installed_flag = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $installedFlag, $clientId);

        $stmt->execute();
    }

    public function createDefaultScreenData($clientId, $currentDate) {

        $screen = Screen::getByName($clientId, 'Default Screen');

        $summaryField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_SUMMARY_CODE);
        Screen::addData($screen['id'], $summaryField['id'], 1, $currentDate);

        $issueTypeField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        Screen::addData($screen['id'], $issueTypeField['id'], 2, $currentDate);
        $priorityField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        Screen::addData($screen['id'], $priorityField['id'], 3, $currentDate);
        $dueDateField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_DUE_DATE_CODE);
        Screen::addData($screen['id'], $dueDateField['id'], 4, $currentDate);
        $componentsField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        Screen::addData($screen['id'], $componentsField['id'], 5, $currentDate);

        $affectsVersionField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);
        Screen::addData($screen['id'], $affectsVersionField['id'], 6, $currentDate);
        $fixVersionField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        Screen::addData($screen['id'], $fixVersionField['id'], 7, $currentDate);

        $assigneeField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 8, $currentDate);

        $reporterField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_REPORTER_CODE);
        Screen::addData($screen['id'], $reporterField['id'], 9, $currentDate);
        $environmentField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);
        Screen::addData($screen['id'], $environmentField['id'], 10, $currentDate);

        $descriptionField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        Screen::addData($screen['id'], $descriptionField['id'], 11, $currentDate);
        $attachmentField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        Screen::addData($screen['id'], $attachmentField['id'], 12, $currentDate);

        $timeTrackingField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        Screen::addData($screen['id'], $timeTrackingField['id'], 13, $currentDate);

        $screen = Screen::getByName($clientId, 'Resolve Issue Screen');
        $assigneeField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 1, $currentDate);

        $fixVersionField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        Screen::addData($screen['id'], $fixVersionField['id'], 2, $currentDate);
        $resolutionField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_RESOLUTION_CODE);
        Screen::addData($screen['id'], $resolutionField['id'], 3, $currentDate);

        $commentField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_COMMENT_CODE);
        Screen::addData($screen['id'], $commentField['id'], 4, $currentDate);

        $screen = Screen::getByName($clientId, 'Workflow Screen');
        $assigneeField = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 1, $currentDate);
    }

    public function createDefaultNotificationScheme($clientId, $currentDate) {
        $query = "INSERT INTO notification_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Notification Scheme';
        $description = 'Default Notification Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultPermissionScheme($clientId, $currentDate) {
        $query = "INSERT INTO permission_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Permission Scheme';
        $description = 'Default Permission Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultIssueTypeFieldConfigurationData($clientId, $issueTypeFieldConfigurationId, $fieldConfigurationId, $currentDate) {
        $issueTypes = Type::getAll($clientId);
        $query = "INSERT INTO  issue_type_field_configuration_data(issue_type_field_configuration_id, issue_type_id, field_configuration_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeFieldConfigurationId . "," . $issueType['id'] . ", " . $fieldConfigurationId . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $currentDate) {
        $issueTypes = Type::getAll($clientId);
        $query = "INSERT INTO issue_type_scheme_data(issue_type_scheme_id, issue_type_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeSchemeId . "," . $issueType['id'] . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getAllIssueSettings($type, $clientId) {
        $query = "SELECT id, name, description FROM issue_" . $type . ' WHERE client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function createDefaultIssueTypes($clientId, $currentDate) {
        $query = "INSERT INTO issue_type(client_id, name, description, sub_task_flag, icon_name, date_created) VALUES " .
            "(" . $clientId . ", 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '" . $currentDate . "'), (" . $clientId . ", 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Task', 'A task that needs to be done.', 0, 'task.png', '" . $currentDate . "'), (" . $clientId . " , 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Story', 'A user story', 0, 'story.png', '" . $currentDate . "'), (" . $clientId . " , 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Technical task', 'A technical task.', 1, 'technical.png', '" . $currentDate . "'), (" . $clientId . " , 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public  function createDefaultIssueTypeScheme($clientId, $type, $currentDate) {
        $query = "INSERT INTO issue_type_scheme(client_id, name, description, type, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Issue Type Scheme';
        $description = 'Default Issue Type Scheme';
        $stmt->bind_param("issss", $clientId, $name, $description, $type, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultIssueTypeScreenScheme($clientId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Issue Type Screen Scheme';
        $description = 'Default Issue Type Screen Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultIssuePriorities($clientId, $currentDate) {
        $query = "INSERT INTO issue_priority(client_id, name, icon_name, color, description, date_created) VALUES " .
            "(" . $clientId . ", 'Minor', 'minor.png', '#006600', 'Minor loss of function, or other problem where easy workaround is present.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Major', 'major.png', '#009900', 'Major loss of function.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Critical', 'critical.png', '#FF0000', 'Crashes, loss of data, severe memory leak.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Blocker', 'blocker.png', '#CC0000', 'Blocks development and/or testing work, production could not run.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Trivial', 'trivial.png', '#003300', 'Cosmetic problem like misspelled words or misaligned text.', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueStatuses($clientId, $currentDate) {
        $query = "INSERT INTO issue_status(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ", 'Open', 'The issue is open and ready for the assignee to start work on it.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '" . $currentDate . "'), " .
            "(" . $clientId . " , 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueResolutions($clientId, $currentDate) {
        $query = "INSERT INTO issue_resolution(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ", 'Fixed', 'A fix for this issue is checked into the tree and tested.', '" . $currentDate . "'), (" . $clientId . ", 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Won\'t Fix', 'The problem described is an issue which will never be fixed.', '" . $currentDate . "'), (" . $clientId . " , 'Duplicate', 'The problem is a duplicate of an existing issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'No Change Required', 'The problems does not require a change.', '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function create($company_name, $companyDomain, $baseURL, $companyEmail, $countryId, $vatNumber = null, $paymillId, $instanceType, $date) {
        $query = "INSERT INTO client(company_name, company_domain, base_url, contact_email, date_created, instance_type, sys_country_id, vat_number, paymill_id) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssssiis", $company_name, $companyDomain, $baseURL, $companyEmail, $date, $instanceType, $countryId, $vatNumber, $paymillId);

        $stmt->execute();

        $clientId = UbirimiContainer::get()['db.connection']->insert_id;

        $query = "INSERT INTO client_settings(client_id, operating_mode, timezone, language, title_name) VALUES (?, ?, ?, ?, ?)";
        $defaultLanguage = 'english';
        $defaultTimezone = 'Europe/London';
        $operatingMode = 'public';
        $titleName = 'Ubirimi.com';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("issss", $clientId, $operatingMode, $defaultTimezone, $defaultLanguage, $titleName);

        $stmt->execute();

        return $clientId;
    }

    public function deleteById($clientId) {

        $clientData = $this->getRepository('ubirimi.general.client')->getById($clientId);
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        // delete Yongo Product data
        $projects = $this->getRepository('ubirimi.general.client')->getProjects($clientId);
        while ($projects && $project = $projects->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository('yongo.project.project')->deleteById($project['id']);
        }

        $workflows = $this->getRepository('yongo.workflow.workflow')->getByClientId($clientId);
        while ($workflows && $workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository('yongo.workflow.workflow')->deleteById($workflow['id']);
        }
        Scheme::deleteByClientId($clientId);

        $screens = Screen::getByClientId($clientId);
        while ($screens && $screen = $screens->fetch_array(MYSQLI_ASSOC)) {
            Screen::deleteById($screen['id']);
        }
        Scheme::deleteByClientId($clientId);

        $this->getRepository('ubirimi.general.client')->deleteYongoIssueTypes($clientId);
        $this->getRepository('ubirimi.general.client')->deleteYongoIssueStatuses($clientId);
        $this->getRepository('ubirimi.general.client')->deleteYongoIssueResolutions($clientId);
        $this->getRepository('ubirimi.general.client')->deleteYongoIssuePriorities($clientId);
        Field::deleteByClientId($clientId);

        Configuration::deleteByClientId($clientId);

        ConfigurationScheme::deleteByClientId($clientId);

        Scheme::deleteByClientId($clientId);
        Scheme::deleteByClientId($clientId);

        TypeScheme::deleteByClientId($clientId);
        TypeScreenScheme::deleteByClientId($clientId);

        // delete issue security schemes

        $issueSecuritySchemes = SecurityScheme::getByClientId($clientId);
        while ($issueSecuritySchemes && $issueSecurityScheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)) {
            SecurityScheme::deleteById($issueSecurityScheme['id']);
        }

        $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId);

        if ($users) {
            $userIdsArray = array();
            while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
                $userIdsArray[] = $user['id'];

                // delete user avatars
                $spaceBasePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, 'user_avatars');
                Util::deleteDir($spaceBasePath . $user['id']);

            }
            $users_ids_string = implode($userIdsArray, ', ');

            $query = 'delete from group_data where user_id IN (' . $users_ids_string . ')';
            UbirimiContainer::get()['db.connection']->query($query);

            $query = 'delete from permission_role_data where default_user_id IN (' . $users_ids_string . ')';
            UbirimiContainer::get()['db.connection']->query($query);
        }

        $this->getRepository('ubirimi.general.client')->deleteGroups($clientId);

        $query = 'delete from permission_role where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from user where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from event where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from client_product where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from client_yongo_settings where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from client_documentator_settings where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from client_smtp_settings where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from client_settings where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from general_log where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from sys_permission_global_data where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);

        // delete Cheetah Product data
        $agileBoards = $this->getRepository('agile.board.board')->getByClientId($clientId, 'array');
        if ($agileBoards) {
            for ($i = 0; $i < count($agileBoards); $i++) {
                $this->getRepository('agile.board.board')->deleteById($agileBoards[$i]['id']);
            }
        }

        // delete Events Product data
        $this->getRepository('ubirimi.general.client')->deleteCalendars($clientId);

        // delete SVN Product data
        $this->getRepository('ubirimi.general.client')->deleteSVNRepositories($clientId);

        // delete Documentador Product data
        $this->getRepository('ubirimi.general.client')->deleteSpaces($clientId);

        $query = 'delete from client where id = ' . $clientId . ' limit 1';
        UbirimiContainer::get()['db.connection']->query($query);

        // also delete paymill information
        $client = new PaymillClient();
        $client->setId($clientData['paymill_id']);

        $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);
        $response = $requestPaymill->delete($client);

        $query = "SET FOREIGN_KEY_CHECKS = 1;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getAll($filters = array()) {
        $query = 'select client.id, company_name, company_domain, address_1, address_2, city, district, contact_email, ' .
                 'date_created, installed_flag, last_login, is_payable, paymill_id, ' .
                 'sys_country.name as country_name, sys_country_id ' .
                 'from client ' .
                 'left join sys_country on sys_country.id = client.sys_country_id
                 where 1 = 1';

        if (!empty($filters['today'])) {
            $query .= " and DATE(date_created) = DATE(NOW())";
        }

        if (!empty($filters['sort_by'])) {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        if (isset($filters['limit'])) {
            $query .= ' limit ' . $filters['limit'];
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getProjects($clientId, $resultType = null, $resultColumn = null, $onlyHelpDeskFlag = false) {
        $partQuery = '';
        if ($onlyHelpDeskFlag) {
            $partQuery = ' AND project.help_desk_enabled_flag = 1 ';
        }
        $query = 'SELECT project.id, code, project.name, project.description, user.first_name, user.last_name, user.id as user_id, ' .
                 'project_category.name as category_name, project_category_id as category_id ' .
                 'FROM project ' .
                 'LEFT JOIN user ON project.lead_id = user.id ' .
                 'left join project_category on project_category.id = project.project_category_id ' .
                 'WHERE project.client_id = ? ' .
                 $partQuery .
                 'ORDER BY project.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($prj = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $prj[$resultColumn];
                    else
                        $resultArray[] = $prj;
                }

                return $resultArray;
            } else return $result;
        } else
            return null;
    }

    public function getUsers($clientId, $filterGroupId = null, $resultType = null, $includeHelpdeskCustomerUsers = 1) {
        $query = 'SELECT user.* ' .
                 'FROM user ' .
                 'left join group_data on group_data.user_id = user.id ' .
                 'WHERE user.client_id = ? ';

        if (!$includeHelpdeskCustomerUsers) {
            $query .= ' AND customer_service_desk_flag = 0';
        }

        $paramType = 'i';
        $paramValue = array();
        $paramValueRef = array();
        $paramValue[] = $clientId;
        if ($filterGroupId) {
            $query .= ' and group_data.group_id = ?';
            $paramType .= 'i';
            $paramValue[] = $filterGroupId;
        }

        $query .= ' group by user.id';
        $query .= ' order by user.first_name, user.last_name';

        foreach ($paramValue as $key => $value)
            $paramValueRef[$key] = &$paramValue[$key];

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($paramType), $paramValueRef));

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            if ($resultType == 'array') {
                $resultArray = array();
                while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $user;
                }
                return $resultArray;
            } else return $result;

        else
            return null;
    }

    public function updateById($clientId, $company_name, $address_1, $address_2, $city, $district, $contact_email, $countryId) {
        $query = 'UPDATE client SET ' .
            'company_name = ?, address_1 = ?, address_2 = ?, city = ?, district = ?, contact_email = ?, sys_country_id = ? ' .
            'WHERE id = ? ' .
            'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssssii", $company_name, $address_1, $address_2, $city, $district, $contact_email, $countryId, $clientId);
        $stmt->execute();
    }

    public function getById($clientId) {
        $query = 'select * from client where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function createDefaultScreens($clientId, $currentDate) {
        $query = "INSERT INTO screen(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ",'Default Screen', 'Allows to update all system fields.', '" . $currentDate . "'), " .
            "(" . $clientId . ",'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ",'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultScreenScheme($clientId, $currentDate) {
        $query = "INSERT INTO screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Screen Scheme';
        $description = 'Default Screen Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultWorkflowScheme($clientId, $currentDate) {
        $query = "INSERT INTO workflow_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Workflow Scheme';
        $description = 'Default Workflow Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultFieldConfiguration($clientId, $currentDate) {
        $query = "INSERT INTO field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Field Configuration';
        $description = 'Default Field Configuration';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultIssueTypeFieldConfiguration($clientId, $currentDate) {
        $query = "INSERT INTO  issue_type_field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Field Configuration Scheme';
        $description = 'Default Field Configuration Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultWorkflowSchemeData($workflowSchemeId, $workflowId, $currentDate) {
        $query = "INSERT INTO workflow_scheme_data(workflow_scheme_id, workflow_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $workflowSchemeId, $workflowId, $currentDate);

        $stmt->execute();
    }

    public function createDefaultScreenSchemeData($clientId, $screenSchemeId, $currentDate) {
        $defaultScreenData = Screen::getByName($clientId, 'Default Screen');
        $defaultScreenId = $defaultScreenData['id'];

        $query = "INSERT INTO screen_scheme_data(screen_scheme_id, sys_operation_id, screen_id, date_created) VALUES " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_CREATE . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_EDIT . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_VIEW . ", " . $defaultScreenId . ", '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $currentDate) {
        $issueTypes = Type::getAll($clientId);
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, screen_scheme_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeScreenSchemeId . "," . $issueType['id'] . ", " . $screenSchemeId . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultWorkflow($clientId, $issueTypeSchemeId, $currentDate) {
        $query = "INSERT INTO workflow(client_id, issue_type_scheme_id, name, description, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Yongo Workflow';
        $description = 'Default Yongo Workflow';

        $stmt->bind_param("iisss", $clientId, $issueTypeSchemeId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function createDefaultWorkflowData($clientId, $workflowId, $currentDate)
    {
        $screenResolutionData = Screen::getByName($clientId, 'Resolve Issue Screen');
        $screenResolutionId = $screenResolutionData['id'];

        $screenWorkflowData = Screen::getByName($clientId, 'Workflow Screen');
        $screenWorkflowId = $screenWorkflowData['id'];

        $createStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, null, 'Create Issue', 1);

        $statusOpenIdData = Settings::getByName($clientId, 'status', 'Open');
        $statusOpenId = $statusOpenIdData['id'];
        $openStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, $statusOpenId, 'Open', 0);

        $statusInProgressIdData = Settings::getByName($clientId, 'status', 'In Progress');
        $statusInProgressId = $statusInProgressIdData['id'];

        $inProgressStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, $statusInProgressId, 'In Progress', 0);

        $statusClosedIdData = Settings::getByName($clientId, 'status', 'Closed');
        $statusClosedId = $statusClosedIdData['id'];
        $closedStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, $statusClosedId, 'Closed', 0);

        $statusResolvedIdData = Settings::getByName($clientId, 'status', 'Resolved');
        $statusResolvedId = $statusResolvedIdData['id'];
        $resolvedStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, $statusResolvedId, 'Resolved', 0);

        $statusReopenedIdData = Settings::getByName($clientId, 'status', 'Reopened');
        $statusReopenedId = $statusReopenedIdData['id'];
        $reopenedStepId = $this->getRepository('yongo.workflow.workflow')->createDefaultStep($workflowId, $statusReopenedId, 'Reopened', 0);

        $eventIssueWorkStoppedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_WORK_STOPPED_CODE, 'id');
        $eventIssueCreatedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_CREATED_CODE, 'id');
        $eventIssueWorkStartedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_WORK_STARTED_CODE, 'id');
        $eventIssueClosedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_CLOSED_CODE, 'id');

        $eventIssueResolvedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_RESOLVED_CODE, 'id');

        $eventIssueReopenedId = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_REOPENED_CODE, 'id');

        // create issue -----> open
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, null, $createStepId, $openStepId, 'Create Issue', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_CREATE_ISSUE, 'create_issue');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueCreatedId);

        // open ------> in progress
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, null, $openStepId, $inProgressStepId, 'Start Progress', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . Condition::CONDITION_ONLY_ASSIGNEE . ')';
        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // open ------> closed
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $openStepId, $closedStepId, 'Close Issue', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';
        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // open ------> resolved

        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $openStepId, $resolvedStepId, 'Resolve Issue', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // in progress ------> open
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, null, $inProgressStepId, $openStepId, 'Stop Progress', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStoppedId);

        $definitionData = '(cond_id=' . Condition::CONDITION_ONLY_ASSIGNEE . ')';
        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // in progress ------> resolved
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $inProgressStepId, $resolvedStepId, 'Resolve Issue', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // in progress ------> closed
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $inProgressStepId, $closedStepId, 'Close Issue', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // resolved ------> closed
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $closedStepId, 'Close Issue', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // resolved ------> reopened
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $reopenedStepId, 'Reopen Issue', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // reopened ------> resolved
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $reopenedStepId, $resolvedStepId, 'Resolve Issue', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // reopened ------> closed
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenResolutionId, $reopenedStepId, $closedStepId, 'Close Issue', '');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // reopened ------> In progress

        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, null, $reopenedStepId, $inProgressStepId, 'Start Progress', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . Condition::CONDITION_ONLY_ASSIGNEE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // closed ------> reopened
        $transitionId = $this->getRepository('yongo.workflow.workflow')->addTransition($workflowId, $screenWorkflowId, $closedStepId, $reopenedStepId, 'Reopen Issue', '');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $this->getRepository('yongo.workflow.workflow')->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $this->getRepository('yongo.workflow.workflow')->addCondition($transitionId, $definitionData);

        // save the position of the elements
        // create node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $createStepId . ", 29, 509)";
        UbirimiContainer::get()['db.connection']->query($query);

        // open node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $openStepId . ", 190, 471)";
        UbirimiContainer::get()['db.connection']->query($query);

        // resolved node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $closedStepId . ", 736, 305)";
        UbirimiContainer::get()['db.connection']->query($query);

        // close node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $resolvedStepId . ", 277, 886)";
        UbirimiContainer::get()['db.connection']->query($query);

        // in progress node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $reopenedStepId . ", 438, 598)";
        UbirimiContainer::get()['db.connection']->query($query);

        // reopened node
        $query = "insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) " .
            "values (" . $workflowId . ", " . $inProgressStepId . ", 680, 867)";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultEvents($clientId, $currentDate) {
        $query = "INSERT INTO event(client_id, name, code, description, system_flag, date_created) VALUES " .
            "(" . $clientId . ",'Issue Created', 1, 'This is the \'issue created\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Updated', 2, 'This is the \'issue updated\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Assigned', 3, 'This is the \'issue assigned\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Resolved', 4, 'This is the \'issue resolved\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Closed', 5, 'This is the \'issue closed\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Commented', 6, 'This is the \'issue commented\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Comment Edited', 7, 'This is the \'issue comment edited\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Reopened', 8, 'This is the \'issue reopened\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Deleted', 9, 'This is the \'issue deleted\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Work Started on Issue', 10, 'This is the \'work started on issue\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Work Stopped on Issue', 11, 'This is the \'work stopped on issue\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Generic Event', 12, 'This is the \'generic event\' event.', 1, '" . $currentDate . "'), " .
            "(" . $clientId . ",'Issue Moved', 13, 'This is the \'issue moved\' event.', 1, '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getYongoSettings($clientId) {
        $query = 'select * from client_yongo_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getSettings($clientId) {
        $query = 'select * from client_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getDocumentatorSettings($clientId) {
        $query = 'select * from client_documentator_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function updateProductSettings($clientId, $targetProduct, $parameters) {
        $tableName = '';

        if ($targetProduct == SystemProduct::SYS_PRODUCT_YONGO)
            $tableName = 'client_yongo_settings';
        else if ($targetProduct == SystemProduct::SYS_PRODUCT_DOCUMENTADOR) {
            $tableName = 'client_documentator_settings';
        } else if ($targetProduct == 'client_settings') {
            $tableName = 'client_settings';
        }

        $query = 'UPDATE ' . $tableName . ' SET ';

        $values = array();
        $values_ref = array();
        $valuesType = '';
        for ($i = 0; $i < count($parameters); $i++) {
            $query .= $parameters[$i]['field'] .= ' = ?, ';
            $values[] = $parameters[$i]['value'];
            $valuesType .= $parameters[$i]['type'];
        }
        $query = substr($query, 0, strlen($query) - 2) . ' ' ;

        $query .= 'WHERE client_id = ? ' .
                  'LIMIT 1';
        $values[] = $clientId;
        $valuesType .= 'i';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        foreach ($values as $key => $value)
            $values_ref[$key] = &$values[$key];

        if ($valuesType != '')
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
        $stmt->execute();

        $result = $stmt->get_result();
    }

    public function getProjectsByPermission($clientId, $userId, $permissionId, $resultType = null) {
        // 1. user in permission scheme

        $queryLoggedInUser = 'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
             'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
             'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join user on user.id = permission_scheme_data.user_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
            'permission_scheme_data.user_id = ? and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'project.id is not null and ' .
            'user.id is not null ' .

        // 2. group in permission scheme

        'UNION DISTINCT ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
             'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
             'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join `group` on group.id = permission_scheme_data.group_id ' .
        'left join `group_data` on group_data.group_id = `group`.id ' .
        'left join user on user.id = group_data.user_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
            'permission_scheme_data.group_id is not null and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'user.id = ? and ' .
            'project.id is not null and ' .
            'user.id is not null ' .

        // 2.1 group Anyone

        'UNION ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, null as first_name, null as last_name, null as user_id, ' .
            'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
            'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
            'from permission_scheme ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join project on project.permission_scheme_id = permission_scheme.id ' .
            'left join project_category on project_category.id = project.project_category_id ' .
            'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
            'where permission_scheme.client_id = ? and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'permission_scheme_data.group_id = 0 ' .

        // 3. permission role in permission scheme - user

        'UNION DISTINCT ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
            'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
            'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
        'left join user on user.id = project_role_data.user_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
            'project_role_data.user_id is not null and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'user.id = ? and ' .
            'user.id is not null and ' .
            'project.id is not null and ' .
            'project_role_data.project_id = project.id ' .

        // 4. permission role in permission scheme - group

        'UNION DISTINCT ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
            'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
            'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
        'left join `group` on group.id = project_role_data.group_id ' .
        'left join `group_data` on group_data.group_id = `group`.id ' .
        'left join user on user.id = group_data.user_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
            'project_role_data.group_id is not null and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'user.id = ? and ' .
            'project.id is not null and ' .
            'user.id is not null ' .

        // 5. reporter

        'UNION DISTINCT ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
        'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
        'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join yongo_issue on yongo_issue.project_id = project.id ' .
        'left join user on user.id = yongo_issue.user_reported_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
        'permission_scheme_data.sys_permission_id = ? and ' .
        'permission_scheme_data.reporter = 1 and ' .
        'user.id = ? and ' .
        'project.id is not null and ' .
        'user.id is not null ' .

        // 6. current assignee

        'UNION DISTINCT ' .

        'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, user.first_name, user.last_name, user.id as user_id, ' .
        'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id, ' .
        'user_lead.first_name as lead_first_name, user_lead.last_name as lead_last_name ' .
        'from permission_scheme ' .
        'left join project on project.permission_scheme_id = permission_scheme.id ' .
        'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
        'left join yongo_issue on yongo_issue.project_id = project.id ' .
        'left join user on user.id = yongo_issue.user_assigned_id ' .
        'LEFT JOIN user user_lead ON project.lead_id = user_lead.id ' .
        'left join project_category on project_category.id = project.project_category_id ' .
        'where permission_scheme.client_id = ? and ' .
        'permission_scheme_data.sys_permission_id = ? and ' .
        'permission_scheme_data.current_assignee = 1 and ' .
        'user.id = ? and ' .
        'project.id is not null and ' .
        'user.id is not null';

        // check to see if group 'Anyone' is in the permission. This is for the case of Anonymous access
        if (!$userId) {

            $queryAnonymousUser = 'SELECT DISTINCT project.id, project.code, project.name, issue_type_screen_scheme_id, project.description, null as first_name, null as last_name, null as user_id, ' .
                'project.issue_type_field_configuration_id, project.lead_id, project.issue_security_scheme_id, project_category.name as category_name, project_category.id as category_id ' .
                'from permission_scheme ' .
                'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
                'left join project on project.permission_scheme_id = permission_scheme.id ' .
                'left join project_category on project_category.id = project.project_category_id ' .
                'where permission_scheme.client_id = ? and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'permission_scheme_data.group_id = 0';

            $stmt = UbirimiContainer::get()['db.connection']->prepare($queryAnonymousUser);
            $stmt->bind_param("ii", $clientId, $permissionId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $stmt = UbirimiContainer::get()['db.connection']->prepare($queryLoggedInUser);
            $stmt->bind_param("iiiiiiiiiiiiiiiiiiii", $clientId, $userId, $permissionId, $clientId, $permissionId, $userId, $clientId, $permissionId, $clientId, $permissionId, $userId, $clientId, $permissionId, $userId, $clientId, $permissionId, $userId, $clientId, $permissionId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $user;
                }
                return $resultArray;
            } else return $result;
        } else {
            return null;
        }
    }

    public function hasProduct($clientId, $productId) {
        $query = 'SELECT * ' .
            'from client_product ' .
            'WHERE client_product.client_id = ? and sys_product_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return true;
        else
            return false;
    }

    public function createDefaultFieldConfigurationData($clientId, $fieldConfigurationId) {
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_COMMENT_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_DUE_DATE_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_REPORTER_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_RESOLUTION_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_SUMMARY_CODE);

        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = $this->getRepository('yongo.field.field')->getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        Configuration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
    }

    public function addDefaultDocumentatorGlobalPermissionData($clientId) {
        $groupAdministrators = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Documentador Administrators');
        $groupUsers = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Documentador Users');

        $groupAdministratorsId = $groupAdministrators['id'];
        $groupUsersId = $groupUsers['id'];

        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, group_id) VALUES (?, ?, ?)";

        // for Administrators group
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $permissionId = GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE;
        $stmt->bind_param("iii", $clientId, $permissionId, $groupAdministratorsId);

        $stmt->execute();

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $permissionId = GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_ADMINISTRATOR;
        $stmt->bind_param("iii", $clientId, $permissionId, $groupAdministratorsId);

        $stmt->execute();

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $permissionId = GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_SYSTEM_ADMINISTRATOR;
        $stmt->bind_param("iii", $clientId, $permissionId, $groupAdministratorsId);

        $stmt->execute();

        // for Users group
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $permissionId = GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE;
        $stmt->bind_param("iii", $clientId, $permissionId, $groupUsers);

        $stmt->execute();
    }

    public function addYongoGlobalPermissionData($clientId, $groupAdministrators, $groupUsers) {
        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, group_id) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $Id = GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS;
        $stmt->bind_param("iii", $clientId, $Id, $groupAdministrators['id']);

        $stmt->execute();

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $Id = GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS;
        $stmt->bind_param("iii", $clientId, $Id, $groupAdministrators['id']);

        $stmt->execute();

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $Id = GlobalPermission::GLOBAL_PERMISSION_YONGO_USERS;
        $stmt->bind_param("iii", $clientId, $Id, $groupUsers['id']);

        $stmt->execute();

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $Id = GlobalPermission::GLOBAL_PERMISSION_YONGO_BULK_CHANGE;
        $stmt->bind_param("iii", $clientId, $Id, $groupUsers['id']);

        $stmt->execute();
    }

    public function createDefaultFields($clientId, $date) {
        $query = "INSERT INTO field(client_id, code, name, description, system_flag, date_created) VALUES " .
            "(" . $clientId . ", 'resolution', 'Resolution', 'Resolution', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'comment', 'Comment', 'Comment', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'summary', 'Summary', 'Summary', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'type', 'Issue Type', 'Issue Type', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'affects_version', 'Affects version/s', 'Affects version/s', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'assignee', 'Assignee', 'Assignee', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'component', 'Component/s', 'Component/s', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'description', 'Description', 'Description', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'due_date', 'Due Date', 'Due Date', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'priority', 'Priority', 'Priority', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'attachment', 'Attachment', 'Attachment', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'environment', 'Environment', 'Environment', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'reporter', 'Reporter', 'Reporter', 1, '" . $date . "'), " .
            "(" . $clientId . ", 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is \' *w *d *h *m \' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, '" . $date . "');";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function checkAvailableDomain($companyDomain) {
        $query = 'SELECT id from client where company_domain = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("s", $companyDomain);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return false;
        } else
            return true;
    }

    public function getProducts($clientId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT * from client_product where client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn)
                        $resultArray[] = $data[$resultColumn];
                    else
                        $resultArray[] = $data;
                }

                return $resultArray;
            } else
                return $result;

        } else
            return null;
    }

    public function createDatabase($sqlDatabaseCreation) {
        UbirimiContainer::get()['db.connection']->multi_query($sqlDatabaseCreation);
        do {

            if ($result = UbirimiContainer::get()['db.connection']->store_result()) {

            }
        } while (UbirimiContainer::get()['db.connection']->next_result());
    }

    public function installYongoProduct($clientId, $userId, $clientCreatedDate) {

        // set default YONGO Product Settings
        $this->getRepository('ubirimi.general.client')->createDefaultYongoSettings($clientId);

        // add default issue priorities, statuses, resolutions
        $this->getRepository('ubirimi.general.client')->createDefaultIssuePriorities($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueStatuses($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueResolutions($clientId, $clientCreatedDate);

        // create default Screens
        $this->getRepository('ubirimi.general.client')->createDefaultScreens($clientId, $clientCreatedDate);

        $screenSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultScreenScheme($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultScreenSchemeData($clientId, $screenSchemeId, $clientCreatedDate);

        // create default issue types
        $this->getRepository('ubirimi.general.client')->createDefaultIssueTypes($clientId, $clientCreatedDate);
        $issueTypeSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeScheme($clientId, 'project', $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $clientCreatedDate);

        // create default workflow issue type scheme
        $workflowIssueTypeSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeScheme($clientId, 'workflow', $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeSchemeData($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);

        // create default issue type screen scheme
        $issueTypeScreenSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeScreenScheme($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $clientCreatedDate);

        // create default events
        $this->getRepository('ubirimi.general.client')->createDefaultEvents($clientId, $clientCreatedDate);

        // create default workflow
        $workflowId = $this->getRepository('ubirimi.general.client')->createDefaultWorkflow($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultWorkflowData($clientId, $workflowId, $clientCreatedDate);

        // create default workflow scheme
        $workflowSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultWorkflowScheme($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultWorkflowSchemeData($workflowSchemeId, $workflowId, $clientCreatedDate);

        // create Default Fields
        $this->getRepository('ubirimi.general.client')->createDefaultFields($clientId, $clientCreatedDate);

        // create default link issue options
        $this->getRepository('ubirimi.general.client')->createDefaultLinkIssueOptions($clientId, $clientCreatedDate);

        // create default field configurations
        $fieldConfigurationId = $this->getRepository('ubirimi.general.client')->createDefaultFieldConfiguration($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultFieldConfigurationData($clientId, $fieldConfigurationId, $clientCreatedDate);
        $issueTypeFieldConfigurationId = $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeFieldConfiguration($clientId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->createDefaultIssueTypeFieldConfigurationData($clientId, $issueTypeFieldConfigurationId, $fieldConfigurationId, $clientCreatedDate);

        $this->getRepository('ubirimi.general.client')->createDefaultScreenData($clientId, $clientCreatedDate);

        // create default permission roles
        UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->gaddDefaultPermissionRoles($clientId, $clientCreatedDate);

        // create default group names
        $this->getRepository('ubirimi.user.group')->addDefaultYongoGroups($clientId, $clientCreatedDate);

        $roleAdministrators = UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->ggetByName($clientId, 'Administrators');

        $roleDevelopers = UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->ggetByName($clientId, 'Developers');
        $roleUsers = UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->ggetByName($clientId, 'Users');

        $groupAdministrators = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Administrators');

        $groupDevelopers = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Developers');
        $groupUsers = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Users');

        UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->gaddDefaultGroups($roleAdministrators['id'], array($groupAdministrators['id']), $clientCreatedDate);
        UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->gaddDefaultGroups($roleDevelopers['id'], array($groupDevelopers['id']), $clientCreatedDate);

        UbirimiContainer::get()['repository']->getRepository('yongo.permission.role')->gaddDefaultGroups($roleUsers['id'], array($groupUsers['id']), $clientCreatedDate);

        // add in Administrators group the current user
        $this->getRepository('ubirimi.user.group')->addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        $this->getRepository('ubirimi.user.group')->addData($groupDevelopers['id'], array($userId), $clientCreatedDate);
        $this->getRepository('ubirimi.user.group')->addData($groupUsers['id'], array($userId), $clientCreatedDate);

        // create default permission scheme
        $permissionSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultPermissionScheme($clientId, $clientCreatedDate);

        Scheme::addDefaultPermissions($permissionSchemeId, $roleAdministrators['id'], $roleDevelopers['id'], $roleUsers['id'], $clientCreatedDate);

        // create default notification scheme
        $notificationSchemeId = $this->getRepository('ubirimi.general.client')->createDefaultNotificationScheme($clientId, $clientCreatedDate);
        Scheme::addDefaultNotifications($clientId, $notificationSchemeId);

        // add global permission
        $this->getRepository('ubirimi.general.client')->addYongoGlobalPermissionData($clientId, $groupAdministrators, $groupUsers);
    }

    public function toggleIssueLinkingFeature($clientId) {
        $query = 'UPDATE client_yongo_settings SET issue_linking_flag = 1 - issue_linking_flag where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
    }

    public function toggleTimeTrackingFeature($clientId) {
        $query = 'UPDATE client_yongo_settings SET time_tracking_flag = 1 - time_tracking_flag where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
    }

    public function createDefaultLinkIssueOptions($clientId, $currentDate) {
        LinkType::add($clientId, 'Relates', 'relates to', 'relates to', $currentDate);
        LinkType::add($clientId, 'Duplicate', 'duplicates', 'is duplicated by', $currentDate);

        LinkType::add($clientId, 'Blocks', 'blocks', 'is blocked by', $currentDate);

        LinkType::add($clientId, 'Cloners', 'clones', 'is cloned by', $currentDate);
    }

    public function updateTimeTrackingSettings($clientId, $hoursPerDay, $daysPerWeek, $defaultUnit) {
        $query = 'UPDATE client_yongo_settings SET time_tracking_hours_per_day = ?, time_tracking_days_per_week = ?, time_tracking_default_unit = ? where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisi", $hoursPerDay, $daysPerWeek, $defaultUnit, $clientId);
        $stmt->execute();
    }

    public function updateSettings($clientId, $parameters) {
        $query = 'UPDATE client SET ';

        $values = array();
        $values_ref = array();
        $valuesType = '';
        for ($i = 0; $i < count($parameters); $i++) {
            $query .= $parameters[$i]['field'] .= ' = ?, ';
            $values[] = $parameters[$i]['value'];
            $valuesType .= $parameters[$i]['type'];
        }
        $query = substr($query, 0, strlen($query) - 2) . ' ' ;

        $query .= 'WHERE id = ? ' .
            'LIMIT 1';
        $values[] = $clientId;
        $valuesType .= 'i';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        foreach ($values as $key => $value) {
            $values_ref[$key] = &$values[$key];
        }

        if ($valuesType != '') {
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
        }
        $stmt->execute();
        $result = $stmt->get_result();
    }

    public function getByContactEmailAddress($emailAddress) {
        $query = 'select contact_email from client where LOWER(contact_email) = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $emailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->num_rows;
        else
            return null;
    }

    public function install($clientId) {
        $clientData = $this->getRepository('ubirimi.general.client')->getById($clientId);
        $userData = $this->getRepository('ubirimi.general.client')->getUsers($clientId);
        $user = $userData->fetch_array(MYSQLI_ASSOC);
        $userId = $user['id'];

        $clientCreatedDate = $clientData['date_created'];

        $this->getRepository('ubirimi.general.client')->installYongoProduct($clientId, $userId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->installDocumentatorProduct($clientId, $userId, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->installCalendarProduct($clientId, $userId, $clientCreatedDate);

        $this->getRepository('ubirimi.general.client')->addProduct($clientId, SystemProduct::SYS_PRODUCT_YONGO, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->addProduct($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->addProduct($clientId, SystemProduct::SYS_PRODUCT_SVN_HOSTING, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->addProduct($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $clientCreatedDate);
        $this->getRepository('ubirimi.general.client')->addProduct($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $clientCreatedDate);

        SMTPServer::add(
            $clientId,
            'Ubirimi Mail Server',
            'The default Ubirimi mail server',
            'notification@ubirimi.com',
            'UBR',
            SMTPServer::PROTOCOL_SECURE_SMTP,
            'smtp.gmail.com',
            587,
            10000,
            1,
            'notification@ubirimi.com',
            'cristinasinaomi1',
            1,
            $clientCreatedDate
        );

        $this->getRepository('ubirimi.general.client')->setInstalledFlag($clientId, 1);
    }

    public function addDefaultDocumentatorUserGroups($clientId, $date) {
        $query = "INSERT INTO `group`(client_id, sys_product_id, name, description, date_created) VALUES (?, ?, ?, ?, ?), (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $group_name_1 = 'Documentador Administrators';
        $group_name_2 = 'Documentador Users';

        $group_descr_1 = 'Documentador Administrators';
        $group_descr_2 = 'Documentador Users';

        $productId = SystemProduct::SYS_PRODUCT_DOCUMENTADOR;
        $stmt->bind_param("iisssiisss", $clientId, $productId, $group_name_1, $group_descr_1, $date, $clientId, $productId, $group_name_2, $group_descr_2, $date);

        $stmt->execute();
    }

    public function addDefaultDocumentatorSettings($clientId) {
        $query = "INSERT INTO client_documentator_settings(client_id, anonymous_use_flag, anonymous_view_user_profile_flag) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $anonymous_use_flag = 0;
        $anonymous_view_user_profile_flag = 50;

        $stmt->bind_param("iii", $clientId, $anonymous_use_flag, $anonymous_view_user_profile_flag);

        $stmt->execute();
    }

    public function getProductById($clientId, $productId) {
        $query = 'SELECT * ' .
            'FROM client_product ' .
            "WHERE client_product.client_id = ? and sys_product_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getByProductIdId($clientId, $productId) {
        $query = 'SELECT * ' .
            'FROM client_product ' .
            "WHERE client_product.client_id = ? and sys_product_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getAdministrators($clientId, $userId = null) {
        $query = "SELECT user.* " .
            "FROM user " .
            "WHERE client_id = ? and client_administrator_flag = 1";
        if ($userId) {
            $query .= ' and user.id != ' . $userId;
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteYongoIssueTypes($clientId) {
        $query = 'delete from issue_type where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteYongoIssueStatuses($clientId) {
        $query = 'delete from issue_status where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteYongoIssueResolutions($clientId) {
        $query = 'delete from issue_resolution where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteYongoIssuePriorities($clientId) {
        $query = 'delete from issue_priority where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteCalendars($clientId) {
        $calendars = Calendar::getByClientId($clientId);
        if ($calendars) {
            while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)) {

                Calendar::deleteById($calendar['id']);
            }
        }
    }

    public function deleteSVNRepositories($clientId) {
        $repositories = SVNRepository::getAllByClientId($clientId);
        if ($repositories) {
            while ($repository = $repositories->fetch_array(MYSQLI_ASSOC)) {
                SVNRepository::deleteAllById($repository['id']);
            }
        }
    }

    public function deleteSpaces($clientId) {
        $spaces = Space::getByClientId($clientId);
        if ($spaces) {
            while ($space = $spaces->fetch_array(MYSQLI_ASSOC)) {
                Space::deleteById($space['space_id']);
            }
        }
    }

    public function installDocumentatorProduct($clientId, $userId, $clientCreatedDate) {
        $this->getRepository('ubirimi.general.client')->addDefaultDocumentatorUserGroups($clientId, $clientCreatedDate);

        $groupAdministrators = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Documentador Administrators');
        $groupUsers = $this->getRepository('ubirimi.user.group')->getByName($clientId, 'Documentador Users');

        // add in Administrators/Users groups the current user
        $this->getRepository('ubirimi.user.group')->addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        $this->getRepository('ubirimi.user.group')->addData($groupUsers['id'], array($userId), $clientCreatedDate);

        $this->getRepository('ubirimi.general.client')->addDefaultDocumentatorSettings($clientId);
        $this->getRepository('ubirimi.general.client')->addDefaultDocumentatorGlobalPermissionData($clientId);
    }

    public function installCalendarProduct($clientId, $userId, $clientCreatedDate) {

        // create default calendar for the first user
        $userData = $this->getRepository('ubirimi.user.user')->getById($userId);

        $calendarId = Calendar::save($userData['id'], $userData['first_name'] . ' ' . $userData['last_name'], 'My default calendar', '#A1FF9E', $clientCreatedDate, 1);

        // add default reminders
        Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, Period::PERIOD_MINUTE, 30);
    }

    public function getCurrentMonthAndDayPayingCustomers() {
        $query = "SELECT client.company_domain,
                         client.contact_email,
                         client.base_url,
                         client.id,
                         general_invoice.number as invoice_number,
                         general_invoice.amount as invoice_amount
                    FROM client
                    left join general_invoice on general_invoice.client_id = client.id
                    WHERE general_invoice.client_id is not null
                    and DAY(general_invoice.date_created) = DAY(NOW())
                    and MONTH(general_invoice.date_created) = MONTH(NOW())
                    AND YEAR(general_invoice.date_created) = YEAR(NOW())";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getCountryById($countryId) {
        $query = 'SELECT * ' .
            'FROM sys_country ' .
            "WHERE id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $countryId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getUsersByClientIdAndProductIdAndFilters($clientId, $productId, $filters) {
        $query = 'SELECT user.* ' .
            'FROM user ' .
            'left join group_data on group_data.user_id = user.id ' .
            'left join `group` on `group`.id = group_data.group_id ' .
            'WHERE user.client_id = ? ' .
            'and group.sys_product_id = ' . $productId . ' ';

        if (array_key_exists('group', $filters) && $filters['group'] != -1) {
            $query .= ' AND group_data.group_id = ' . $filters['group'];
        }

        if (array_key_exists('username', $filters) && !empty($filters['username'])) {
            $query .= " AND user.username like '%" . $filters['username'] . "%'";
        }

        if (array_key_exists('fullname', $filters) && !empty($filters['fullname'])) {
            $query .= " AND CONCAT(first_name, last_name) LIKE '%" . $filters['fullname'] . "%'";
        }

        $query .= ' group by user.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getGroupsByClientIdAndProductIdAndFilters($clientId, $productId, $filters) {
        $query = 'SELECT `group`.* ' .
            'FROM `group` ' .
            'left join group_data on group_data.group_id = `group`.id ' .
            'WHERE `group`.client_id = ? ' .
            'and `group`.sys_product_id = ' . $productId . ' ';

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query .= " AND `group`.name like '%" . $filters['name'] . "%'";
        }

        $query .= ' group by `group`.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function updateLoginTime($clientId, $datetime)
    {
        $query = "UPDATE client SET last_login = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $datetime, $clientId);
        $stmt->execute();
    }

    public function updatePaymillId($clientPaymillId, $clientId) {
        $query = "UPDATE client SET paymill_id = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $clientPaymillId, $clientId);
        $stmt->execute();
    }
}
