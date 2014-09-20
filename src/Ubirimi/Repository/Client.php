<?php

namespace Ubirimi\Repository;

use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
use Ubirimi\Calendar\Repository\CalendarReminderType;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Documentador\Space;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\User\User;
use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionRole;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Request as PaymillRequest;

class Client
{
    const INSTANCE_TYPE_ON_DEMAND = 1;
    const INSTANCE_TYPE_DOWNLOAD = 2;

    public static function getClientIdAnonymous() {
        $httpHOST = Util::getHttpHost();
        return Client::getByBaseURL($httpHOST, 'array', 'id');
    }

    public static function deleteGroups($clientId) {
        $groups = Group::getByClientId($clientId);
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            Group::deleteByIdForYongo($group['id']);

            Group::deleteByIdForDocumentator($group['id']);
        }
    }

    public static function addProduct($clientId, $productId, $date) {
        $query = "INSERT INTO client_product(client_id, sys_product_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $clientId, $productId, $date);
        $stmt->execute();
    }

    public static function deleteProduct($clientId, $productId) {
        $query = "delete from client_product where client_id = ? and sys_product_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
    }

    public static function getByBaseURL($httpHOST, $resultType = null, $resultColumn = null) {
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

    public static function getYongoSetting($clientId, $settingName) {
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

    public static function getLastMonthActiveClients() {
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

    public static function getSettingsByBaseURL($url) {
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

    public static function createDefaultYongoSettings($clientId) {
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

    public static function setInstalledFlag($clientId, $installedFlag) {
        $query = "update client set installed_flag = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $installedFlag, $clientId);

        $stmt->execute();
    }

    public static function createDefaultScreenData($clientId, $currentDate) {

        $screen = Screen::getByName($clientId, 'Default Screen');

        $summaryField = Field::getByCode($clientId, Field::FIELD_SUMMARY_CODE);
        Screen::addData($screen['id'], $summaryField['id'], 1, $currentDate);

        $issueTypeField = Field::getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        Screen::addData($screen['id'], $issueTypeField['id'], 2, $currentDate);
        $priorityField = Field::getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        Screen::addData($screen['id'], $priorityField['id'], 3, $currentDate);
        $dueDateField = Field::getByCode($clientId, Field::FIELD_DUE_DATE_CODE);
        Screen::addData($screen['id'], $dueDateField['id'], 4, $currentDate);
        $componentsField = Field::getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        Screen::addData($screen['id'], $componentsField['id'], 5, $currentDate);

        $affectsVersionField = Field::getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);
        Screen::addData($screen['id'], $affectsVersionField['id'], 6, $currentDate);
        $fixVersionField = Field::getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        Screen::addData($screen['id'], $fixVersionField['id'], 7, $currentDate);

        $assigneeField = Field::getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 8, $currentDate);

        $reporterField = Field::getByCode($clientId, Field::FIELD_REPORTER_CODE);
        Screen::addData($screen['id'], $reporterField['id'], 9, $currentDate);
        $environmentField = Field::getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);
        Screen::addData($screen['id'], $environmentField['id'], 10, $currentDate);

        $descriptionField = Field::getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        Screen::addData($screen['id'], $descriptionField['id'], 11, $currentDate);
        $attachmentField = Field::getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        Screen::addData($screen['id'], $attachmentField['id'], 12, $currentDate);

        $timeTrackingField = Field::getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        Screen::addData($screen['id'], $timeTrackingField['id'], 13, $currentDate);

        $screen = Screen::getByName($clientId, 'Resolve Issue Screen');
        $assigneeField = Field::getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 1, $currentDate);

        $fixVersionField = Field::getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        Screen::addData($screen['id'], $fixVersionField['id'], 2, $currentDate);
        $resolutionField = Field::getByCode($clientId, Field::FIELD_RESOLUTION_CODE);
        Screen::addData($screen['id'], $resolutionField['id'], 3, $currentDate);

        $commentField = Field::getByCode($clientId, Field::FIELD_COMMENT_CODE);
        Screen::addData($screen['id'], $commentField['id'], 4, $currentDate);

        $screen = Screen::getByName($clientId, 'Workflow Screen');
        $assigneeField = Field::getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        Screen::addData($screen['id'], $assigneeField['id'], 1, $currentDate);
    }

    public static function createDefaultNotificationScheme($clientId, $currentDate) {
        $query = "INSERT INTO notification_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Notification Scheme';
        $description = 'Default Notification Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultPermissionScheme($clientId, $currentDate) {
        $query = "INSERT INTO permission_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Permission Scheme';
        $description = 'Default Permission Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultIssueTypeFieldConfigurationData($clientId, $issueTypeFieldConfigurationId, $fieldConfigurationId, $currentDate) {
        $issueTypes = IssueType::getAll($clientId);
        $query = "INSERT INTO  issue_type_field_configuration_data(issue_type_field_configuration_id, issue_type_id, field_configuration_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeFieldConfigurationId . "," . $issueType['id'] . ", " . $fieldConfigurationId . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $currentDate) {
        $issueTypes = IssueType::getAll($clientId);
        $query = "INSERT INTO issue_type_scheme_data(issue_type_scheme_id, issue_type_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeSchemeId . "," . $issueType['id'] . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function getAllIssueSettings($type, $clientId) {
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

    public static function createDefaultIssueTypes($clientId, $currentDate) {
        $query = "INSERT INTO issue_type(client_id, name, description, sub_task_flag, icon_name, date_created) VALUES " .
            "(" . $clientId . ", 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '" . $currentDate . "'), (" . $clientId . ", 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Task', 'A task that needs to be done.', 0, 'task.png', '" . $currentDate . "'), (" . $clientId . " , 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Story', 'A user story', 0, 'story.png', '" . $currentDate . "'), (" . $clientId . " , 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Technical task', 'A technical task.', 1, 'technical.png', '" . $currentDate . "'), (" . $clientId . " , 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public  static function createDefaultIssueTypeScheme($clientId, $type, $currentDate) {
        $query = "INSERT INTO issue_type_scheme(client_id, name, description, type, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Issue Type Scheme';
        $description = 'Default Issue Type Scheme';
        $stmt->bind_param("issss", $clientId, $name, $description, $type, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultIssueTypeScreenScheme($clientId, $currentDate) {
        $query = "INSERT INTO issue_type_screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Issue Type Screen Scheme';
        $description = 'Default Issue Type Screen Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultIssuePriorities($clientId, $currentDate) {
        $query = "INSERT INTO issue_priority(client_id, name, icon_name, color, description, date_created) VALUES " .
            "(" . $clientId . ", 'Minor', 'minor.png', '#006600', 'Minor loss of function, or other problem where easy workaround is present.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Major', 'major.png', '#009900', 'Major loss of function.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Critical', 'critical.png', '#FF0000', 'Crashes, loss of data, severe memory leak.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Blocker', 'blocker.png', '#CC0000', 'Blocks development and/or testing work, production could not run.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Trivial', 'trivial.png', '#003300', 'Cosmetic problem like misspelled words or misaligned text.', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultIssueStatuses($clientId, $currentDate) {
        $query = "INSERT INTO issue_status(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ", 'Open', 'The issue is open and ready for the assignee to start work on it.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '" . $currentDate . "'), " .
            "(" . $clientId . " , 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultIssueResolutions($clientId, $currentDate) {
        $query = "INSERT INTO issue_resolution(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ", 'Fixed', 'A fix for this issue is checked into the tree and tested.', '" . $currentDate . "'), (" . $clientId . ", 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'Won\'t Fix', 'The problem described is an issue which will never be fixed.', '" . $currentDate . "'), (" . $clientId . " , 'Duplicate', 'The problem is a duplicate of an existing issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ", 'No Change Required', 'The problems does not require a change.', '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function create($company_name, $companyDomain, $baseURL, $companyEmail, $countryId, $vatNumber = null, $paymillId, $instanceType, $date) {
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

    public static function deleteById($clientId) {

        $clientData = Client::getById($clientId);
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        // delete Yongo Product data
        $projects = Client::getProjects($clientId);
        while ($projects && $project = $projects->fetch_array(MYSQLI_ASSOC)) {
            Project::deleteById($project['id']);
        }

        $workflows = Workflow::getByClientId($clientId);
        while ($workflows && $workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
            Workflow::deleteById($workflow['id']);
        }
        WorkflowScheme::deleteByClientId($clientId);

        $screens = Screen::getByClientId($clientId);
        while ($screens && $screen = $screens->fetch_array(MYSQLI_ASSOC)) {
            Screen::deleteById($screen['id']);
        }
        ScreenScheme::deleteByClientId($clientId);

        Client::deleteYongoIssueTypes($clientId);
        Client::deleteYongoIssueStatuses($clientId);
        Client::deleteYongoIssueResolutions($clientId);
        Client::deleteYongoIssuePriorities($clientId);
        Field::deleteByClientId($clientId);

        FieldConfiguration::deleteByClientId($clientId);

        FieldConfigurationScheme::deleteByClientId($clientId);

        PermissionScheme::deleteByClientId($clientId);
        NotificationScheme::deleteByClientId($clientId);

        IssueTypeScheme::deleteByClientId($clientId);
        IssueTypeScreenScheme::deleteByClientId($clientId);

        // delete issue security schemes

        $issueSecuritySchemes = IssueSecurityScheme::getByClientId($clientId);
        while ($issueSecuritySchemes && $issueSecurityScheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)) {
            IssueSecurityScheme::deleteById($issueSecurityScheme['id']);
        }

        $users = Client::getUsers($clientId);

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

        Client::deleteGroups($clientId);

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
        $agileBoards = AgileBoard::getByClientId($clientId, 'array');
        if ($agileBoards) {
            for ($i = 0; $i < count($agileBoards); $i++) {
                AgileBoard::deleteById($agileBoards[$i]['id']);
            }
        }

        // delete Events Product data
        Client::deleteCalendars($clientId);

        // delete SVN Product data
        Client::deleteSVNRepositories($clientId);

        // delete Documentador Product data
        Client::deleteSpaces($clientId);

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

    public static function getAll($filters = array()) {
        $query = 'select client.id, company_name, company_domain, address_1, address_2, city, district, contact_email, date_created, installed_flag, last_login, ' .
                 'sys_country.name as country_name ' .
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

    public static function getProjects($clientId, $resultType = null, $resultColumn = null, $onlyHelpDeskFlag = false) {
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

    public static function getUsers($clientId, $filterGroupId = null, $resultType = null, $includeHelpdeskCustomerUsers = 1) {
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

    public static function updateById($clientId, $company_name, $address_1, $address_2, $city, $district, $contact_email, $countryId) {
        $query = 'UPDATE client SET ' .
            'company_name = ?, address_1 = ?, address_2 = ?, city = ?, district = ?, contact_email = ?, sys_country_id = ? ' .
            'WHERE id = ? ' .
            'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssssssii", $company_name, $address_1, $address_2, $city, $district, $contact_email, $countryId, $clientId);
        $stmt->execute();
    }

    public static function getById($clientId) {
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

    public static function createDefaultScreens($clientId, $currentDate) {
        $query = "INSERT INTO screen(client_id, name, description, date_created) VALUES " .
            "(" . $clientId . ",'Default Screen', 'Allows to update all system fields.', '" . $currentDate . "'), " .
            "(" . $clientId . ",'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '" . $currentDate . "'), " .
            "(" . $clientId . ",'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '" . $currentDate . "');";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultScreenScheme($clientId, $currentDate) {
        $query = "INSERT INTO screen_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Screen Scheme';
        $description = 'Default Screen Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultWorkflowScheme($clientId, $currentDate) {
        $query = "INSERT INTO workflow_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Workflow Scheme';
        $description = 'Default Workflow Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultFieldConfiguration($clientId, $currentDate) {
        $query = "INSERT INTO field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Field Configuration';
        $description = 'Default Field Configuration';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultIssueTypeFieldConfiguration($clientId, $currentDate) {
        $query = "INSERT INTO  issue_type_field_configuration(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Field Configuration Scheme';
        $description = 'Default Field Configuration Scheme';

        $stmt->bind_param("isss", $clientId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultWorkflowSchemeData($workflowSchemeId, $workflowId, $currentDate) {
        $query = "INSERT INTO workflow_scheme_data(workflow_scheme_id, workflow_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $workflowSchemeId, $workflowId, $currentDate);

        $stmt->execute();
    }

    public static function createDefaultScreenSchemeData($clientId, $screenSchemeId, $currentDate) {
        $defaultScreenData = Screen::getByName($clientId, 'Default Screen');
        $defaultScreenId = $defaultScreenData['id'];

        $query = "INSERT INTO screen_scheme_data(screen_scheme_id, sys_operation_id, screen_id, date_created) VALUES " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_CREATE . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_EDIT . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_VIEW . ", " . $defaultScreenId . ", '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $currentDate) {
        $issueTypes = IssueType::getAll($clientId);
        $query = "INSERT INTO issue_type_screen_scheme_data(issue_type_screen_scheme_id, issue_type_id, screen_scheme_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeScreenSchemeId . "," . $issueType['id'] . ", " . $screenSchemeId . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function createDefaultWorkflow($clientId, $issueTypeSchemeId, $currentDate) {
        $query = "INSERT INTO workflow(client_id, issue_type_scheme_id, name, description, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $name = 'Default Yongo Workflow';
        $description = 'Default Yongo Workflow';

        $stmt->bind_param("iisss", $clientId, $issueTypeSchemeId, $name, $description, $currentDate);

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function createDefaultWorkflowData($clientId, $workflowId, $currentDate)
    {
        $screenResolutionData = Screen::getByName($clientId, 'Resolve Issue Screen');
        $screenResolutionId = $screenResolutionData['id'];

        $screenWorkflowData = Screen::getByName($clientId, 'Workflow Screen');
        $screenWorkflowId = $screenWorkflowData['id'];

        $createStepId = Workflow::createDefaultStep($workflowId, null, 'Create Issue', 1);

        $statusOpenIdData = IssueSettings::getByName($clientId, 'status', 'Open');
        $statusOpenId = $statusOpenIdData['id'];
        $openStepId = Workflow::createDefaultStep($workflowId, $statusOpenId, 'Open', 0);

        $statusInProgressIdData = IssueSettings::getByName($clientId, 'status', 'In Progress');
        $statusInProgressId = $statusInProgressIdData['id'];

        $inProgressStepId = Workflow::createDefaultStep($workflowId, $statusInProgressId, 'In Progress', 0);

        $statusClosedIdData = IssueSettings::getByName($clientId, 'status', 'Closed');
        $statusClosedId = $statusClosedIdData['id'];
        $closedStepId = Workflow::createDefaultStep($workflowId, $statusClosedId, 'Closed', 0);

        $statusResolvedIdData = IssueSettings::getByName($clientId, 'status', 'Resolved');
        $statusResolvedId = $statusResolvedIdData['id'];
        $resolvedStepId = Workflow::createDefaultStep($workflowId, $statusResolvedId, 'Resolved', 0);

        $statusReopenedIdData = IssueSettings::getByName($clientId, 'status', 'Reopened');
        $statusReopenedId = $statusReopenedIdData['id'];
        $reopenedStepId = Workflow::createDefaultStep($workflowId, $statusReopenedId, 'Reopened', 0);

        $eventIssueWorkStoppedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE, 'id');
        $eventIssueCreatedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE, 'id');
        $eventIssueWorkStartedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE, 'id');
        $eventIssueClosedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CLOSED_CODE, 'id');

        $eventIssueResolvedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_RESOLVED_CODE, 'id');

        $eventIssueReopenedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_REOPENED_CODE, 'id');

        // create issue -----> open
        $transitionId = Workflow::addTransition($workflowId, null, $createStepId, $openStepId, 'Create Issue', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_CREATE_ISSUE, 'create_issue');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueCreatedId);

        // open ------> in progress
        $transitionId = Workflow::addTransition($workflowId, null, $openStepId, $inProgressStepId, 'Start Progress', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';
        Workflow::addCondition($transitionId, $definitionData);

        // open ------> closed
        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $openStepId, $closedStepId, 'Close Issue', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';
        Workflow::addCondition($transitionId, $definitionData);

        // open ------> resolved

        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $openStepId, $resolvedStepId, 'Resolve Issue', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // in progress ------> open
        $transitionId = Workflow::addTransition($workflowId, null, $inProgressStepId, $openStepId, 'Stop Progress', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStoppedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';
        Workflow::addCondition($transitionId, $definitionData);

        // in progress ------> resolved
        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $inProgressStepId, $resolvedStepId, 'Resolve Issue', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        Workflow::addCondition($transitionId, $definitionData);

        // in progress ------> closed
        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $inProgressStepId, $closedStepId, 'Close Issue', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // resolved ------> closed
        $transitionId = Workflow::addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $closedStepId, 'Close Issue', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // resolved ------> reopened
        $transitionId = Workflow::addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $reopenedStepId, 'Reopen Issue', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        Workflow::addCondition($transitionId, $definitionData);

        // reopened ------> resolved
        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $reopenedStepId, $resolvedStepId, 'Resolve Issue', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // reopened ------> closed
        $transitionId = Workflow::addTransition($workflowId, $screenResolutionId, $reopenedStepId, $closedStepId, 'Close Issue', '');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // reopened ------> In progress

        $transitionId = Workflow::addTransition($workflowId, null, $reopenedStepId, $inProgressStepId, 'Start Progress', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';

        Workflow::addCondition($transitionId, $definitionData);

        // closed ------> reopened
        $transitionId = Workflow::addTransition($workflowId, $screenWorkflowId, $closedStepId, $reopenedStepId, 'Reopen Issue', '');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        Workflow::addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        Workflow::addCondition($transitionId, $definitionData);

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

    public static function createDefaultEvents($clientId, $currentDate) {
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

    public static function getYongoSettings($clientId) {
        $query = 'select * from client_yongo_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public static function getSettings($clientId) {
        $query = 'select * from client_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public static function getDocumentatorSettings($clientId) {
        $query = 'select * from client_documentator_settings where client_id = ' . $clientId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public static function updateProductSettings($clientId, $targetProduct, $parameters) {
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

    public static function getProjectsByPermission($clientId, $userId, $permissionId, $resultType = null) {
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
            $stmt->bind_param("iiiiiiiiiiiiiiiii", $clientId, $userId, $permissionId, $clientId, $permissionId, $userId, $clientId, $permissionId, $clientId, $permissionId, $userId, $clientId, $permissionId, $userId, $clientId, $permissionId, $userId);
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

    public static function hasProduct($clientId, $productId) {
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

    public static function createDefaultFieldConfigurationData($clientId, $fieldConfigurationId) {
        $field = Field::getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_COMMENT_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = Field::getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = Field::getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = Field::getByCode($clientId, Field::FIELD_DUE_DATE_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = Field::getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = Field::getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_REPORTER_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');

        $field = Field::getByCode($clientId, Field::FIELD_RESOLUTION_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = Field::getByCode($clientId, Field::FIELD_SUMMARY_CODE);

        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = Field::getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        FieldConfiguration::addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
    }

    public static function addDefaultDocumentatorGlobalPermissionData($clientId) {
        $groupAdministrators = Group::getByName($clientId, 'Documentador Administrators');
        $groupUsers = Group::getByName($clientId, 'Documentador Users');

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

    public static function addYongoGlobalPermissionData($clientId, $groupAdministrators, $groupUsers) {
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

    public static function createDefaultFields($clientId, $date) {
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

    public static function checkAvailableDomain($companyDomain) {
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

    public static function getProducts($clientId, $resultType = null, $resultColumn = null) {
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

    public static function createDatabase($sqlDatabaseCreation) {
        UbirimiContainer::get()['db.connection']->multi_query($sqlDatabaseCreation);
        do {

            if ($result = UbirimiContainer::get()['db.connection']->store_result()) {

            }
        } while (UbirimiContainer::get()['db.connection']->next_result());
    }

    public static function installYongoProduct($clientId, $userId, $clientCreatedDate) {

        // set default YONGO Product Settings
        Client::createDefaultYongoSettings($clientId);

        // add default issue priorities, statuses, resolutions
        Client::createDefaultIssuePriorities($clientId, $clientCreatedDate);
        Client::createDefaultIssueStatuses($clientId, $clientCreatedDate);
        Client::createDefaultIssueResolutions($clientId, $clientCreatedDate);

        // create default Screens
        Client::createDefaultScreens($clientId, $clientCreatedDate);

        $screenSchemeId = Client::createDefaultScreenScheme($clientId, $clientCreatedDate);
        Client::createDefaultScreenSchemeData($clientId, $screenSchemeId, $clientCreatedDate);

        // create default issue types
        Client::createDefaultIssueTypes($clientId, $clientCreatedDate);
        $issueTypeSchemeId = Client::createDefaultIssueTypeScheme($clientId, 'project', $clientCreatedDate);
        Client::createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $clientCreatedDate);

        // create default workflow issue type scheme
        $workflowIssueTypeSchemeId = Client::createDefaultIssueTypeScheme($clientId, 'workflow', $clientCreatedDate);
        Client::createDefaultIssueTypeSchemeData($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);

        // create default issue type screen scheme
        $issueTypeScreenSchemeId = Client::createDefaultIssueTypeScreenScheme($clientId, $clientCreatedDate);
        Client::createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $clientCreatedDate);

        // create default events
        Client::createDefaultEvents($clientId, $clientCreatedDate);

        // create default workflow
        $workflowId = Client::createDefaultWorkflow($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);
        Client::createDefaultWorkflowData($clientId, $workflowId, $clientCreatedDate);

        // create default workflow scheme
        $workflowSchemeId = Client::createDefaultWorkflowScheme($clientId, $clientCreatedDate);
        Client::createDefaultWorkflowSchemeData($workflowSchemeId, $workflowId, $clientCreatedDate);

        // create Default Fields
        Client::createDefaultFields($clientId, $clientCreatedDate);

        // create default link issue options
        Client::createDefaultLinkIssueOptions($clientId, $clientCreatedDate);

        // create default field configurations
        $fieldConfigurationId = Client::createDefaultFieldConfiguration($clientId, $clientCreatedDate);
        Client::createDefaultFieldConfigurationData($clientId, $fieldConfigurationId, $clientCreatedDate);
        $issueTypeFieldConfigurationId = Client::createDefaultIssueTypeFieldConfiguration($clientId, $clientCreatedDate);
        Client::createDefaultIssueTypeFieldConfigurationData($clientId, $issueTypeFieldConfigurationId, $fieldConfigurationId, $clientCreatedDate);

        Client::createDefaultScreenData($clientId, $clientCreatedDate);

        // create default permission roles
        PermissionRole::addDefaultPermissionRoles($clientId, $clientCreatedDate);

        // create default group names
        Group::addDefaultYongoGroups($clientId, $clientCreatedDate);

        $roleAdministrators = PermissionRole::getByName($clientId, 'Administrators');

        $roleDevelopers = PermissionRole::getByName($clientId, 'Developers');
        $roleUsers = PermissionRole::getByName($clientId, 'Users');

        $groupAdministrators = Group::getByName($clientId, 'Administrators');

        $groupDevelopers = Group::getByName($clientId, 'Developers');
        $groupUsers = Group::getByName($clientId, 'Users');

        PermissionRole::addDefaultGroups($roleAdministrators['id'], array($groupAdministrators['id']), $clientCreatedDate);
        PermissionRole::addDefaultGroups($roleDevelopers['id'], array($groupDevelopers['id']), $clientCreatedDate);

        PermissionRole::addDefaultGroups($roleUsers['id'], array($groupUsers['id']), $clientCreatedDate);

        // add in Administrators group the current user
        Group::addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        Group::addData($groupDevelopers['id'], array($userId), $clientCreatedDate);
        Group::addData($groupUsers['id'], array($userId), $clientCreatedDate);

        // create default permission scheme
        $permissionSchemeId = Client::createDefaultPermissionScheme($clientId, $clientCreatedDate);

        PermissionScheme::addDefaultPermissions($permissionSchemeId, $roleAdministrators['id'], $roleDevelopers['id'], $roleUsers['id'], $clientCreatedDate);

        // create default notification scheme
        $notificationSchemeId = Client::createDefaultNotificationScheme($clientId, $clientCreatedDate);
        NotificationScheme::addDefaultNotifications($clientId, $notificationSchemeId);

        // add global permission
        Client::addYongoGlobalPermissionData($clientId, $groupAdministrators, $groupUsers);
    }

    public static function toggleIssueLinkingFeature($clientId) {
        $query = 'UPDATE client_yongo_settings SET issue_linking_flag = 1 - issue_linking_flag where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
    }

    public static function toggleTimeTrackingFeature($clientId) {
        $query = 'UPDATE client_yongo_settings SET time_tracking_flag = 1 - time_tracking_flag where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
    }

    public static function createDefaultLinkIssueOptions($clientId, $currentDate) {
        IssueLinkType::add($clientId, 'Relates', 'relates to', 'relates to', $currentDate);
        IssueLinkType::add($clientId, 'Duplicate', 'duplicates', 'is duplicated by', $currentDate);

        IssueLinkType::add($clientId, 'Blocks', 'blocks', 'is blocked by', $currentDate);

        IssueLinkType::add($clientId, 'Cloners', 'clones', 'is cloned by', $currentDate);
    }

    public static function updateTimeTrackingSettings($clientId, $hoursPerDay, $daysPerWeek, $defaultUnit) {
        $query = 'UPDATE client_yongo_settings SET time_tracking_hours_per_day = ?, time_tracking_days_per_week = ?, time_tracking_default_unit = ? where client_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisi", $hoursPerDay, $daysPerWeek, $defaultUnit, $clientId);
        $stmt->execute();
    }

    public static function updateSettings($clientId, $parameters) {
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

        foreach ($values as $key => $value)
            $values_ref[$key] = &$values[$key];

        if ($valuesType != '')
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
        $stmt->execute();
        $result = $stmt->get_result();
    }

    public static function getByContactEmailAddress($emailAddress) {
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

    public static function install($clientId) {
        $clientData = Client::getById($clientId);
        $userData = Client::getUsers($clientId);
        $user = $userData->fetch_array(MYSQLI_ASSOC);
        $userId = $user['id'];

        $clientCreatedDate = $clientData['date_created'];

        Client::installYongoProduct($clientId, $userId, $clientCreatedDate);
        Client::installDocumentatorProduct($clientId, $userId, $clientCreatedDate);
        Client::installCalendarProduct($clientId, $userId, $clientCreatedDate);

        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_YONGO, $clientCreatedDate);
        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $clientCreatedDate);
        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_SVN_HOSTING, $clientCreatedDate);
        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $clientCreatedDate);
        Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $clientCreatedDate);

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

        Client::setInstalledFlag($clientId, 1);
    }

    public static function addDefaultDocumentatorUserGroups($clientId, $date) {
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

    public static function addDefaultDocumentatorSettings($clientId) {
        $query = "INSERT INTO client_documentator_settings(client_id, anonymous_use_flag, anonymous_view_user_profile_flag) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $anonymous_use_flag = 0;
        $anonymous_view_user_profile_flag = 50;

        $stmt->bind_param("iii", $clientId, $anonymous_use_flag, $anonymous_view_user_profile_flag);

        $stmt->execute();
    }

    public static function getProductById($clientId, $productId) {
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

    public static function getByProductIdId($clientId, $productId) {
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

    public static function getAdministrators($clientId, $userId = null) {
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

    public static function deleteYongoIssueTypes($clientId) {
        $query = 'delete from issue_type where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteYongoIssueStatuses($clientId) {
        $query = 'delete from issue_status where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteYongoIssueResolutions($clientId) {
        $query = 'delete from issue_resolution where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteYongoIssuePriorities($clientId) {
        $query = 'delete from issue_priority where client_id = ' . $clientId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteCalendars($clientId) {
        $calendars = Calendar::getByClientId($clientId);
        if ($calendars) {
            while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)) {

                Calendar::deleteById($calendar['id']);
            }
        }
    }

    public static function deleteSVNRepositories($clientId) {
        $repositories = SVNRepository::getAllByClientId($clientId);
        if ($repositories) {
            while ($repository = $repositories->fetch_array(MYSQLI_ASSOC)) {
                SVNRepository::deleteAllById($repository['id']);
            }
        }
    }

    public static function deleteSpaces($clientId) {
        $spaces = Space::getByClientId($clientId);
        if ($spaces) {
            while ($space = $spaces->fetch_array(MYSQLI_ASSOC)) {
                Space::deleteById($space['space_id']);
            }
        }
    }

    public static function installDocumentatorProduct($clientId, $userId, $clientCreatedDate) {
        Client::addDefaultDocumentatorUserGroups($clientId, $clientCreatedDate);

        $groupAdministrators = Group::getByName($clientId, 'Documentador Administrators');
        $groupUsers = Group::getByName($clientId, 'Documentador Users');

        // add in Administrators/Users groups the current user
        Group::addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        Group::addData($groupUsers['id'], array($userId), $clientCreatedDate);

        Client::addDefaultDocumentatorSettings($clientId);
        Client::addDefaultDocumentatorGlobalPermissionData($clientId);
    }

    public static function installCalendarProduct($clientId, $userId, $clientCreatedDate) {

        // create default calendar for the first user
        $userData = User::getById($userId);

        $calendarId = Calendar::save($userData['id'], $userData['first_name'] . ' ' . $userData['last_name'], 'My default calendar', '#A1FF9E', $clientCreatedDate, 1);

        // add default reminders
        Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, CalendarEventReminderPeriod::PERIOD_MINUTE, 30);
    }

    public static function getCurrentMonthAndDayPayingCustomers() {
        $query = "SELECT client.company_domain,
                         client.contact_email,
                         client.base_url,
                         client.id,
                         general_invoice.nr as invoice_number,
                         general_invoice.amount as invoice_amount
                    FROM client
                    left join general_invoice on general_invoice.client_id = client.id
                    WHERE general_invoice.client_id is not null
                    and DAY(general_invoice.date_created) = DAY(NOW())
                    and MONTH(general_invoice.date_created) = MONTH(NOW())
                    AND YEAR(general_invoice.date_created) = YEAR(NOW()))";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getCountryById($countryId) {
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

    public static function getUsersByClientIdAndProductIdAndFilters($clientId, $productId, $filters) {
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

    public static function getGroupsByClientIdAndProductIdAndFilters($clientId, $productId, $filters) {
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

    public static function updateLoginTime($clientId, $datetime)
    {
        $query = "UPDATE client SET last_login = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $datetime, $clientId);
        $stmt->execute();
    }

    public static function updatePaymillId($clientPaymillId, $clientId) {
        $query = "UPDATE client SET paymill_id = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $clientPaymillId, $clientId);
        $stmt->execute();
    }
}
