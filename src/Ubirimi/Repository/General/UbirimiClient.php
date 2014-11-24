<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Repository\General;

use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Request as PaymillRequest;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Reminder\ReminderPeriod;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class UbirimiClient
{
    const INSTANCE_TYPE_ON_DEMAND = 1;
    const INSTANCE_TYPE_DOWNLOAD = 2;

    public function getClientIdAnonymous() {
        $httpHOST = Util::getHttpHost();
        return UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
    }

    public function deleteGroups($clientId) {
        $groups = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByClientId($clientId);
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->deleteByIdForYongo($group['id']);

            UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->deleteByIdForDocumentador($group['id']);
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

        $screenRepository = UbirimiContainer::get()['repository']->get(Screen::class);
        $fieldRepository = UbirimiContainer::get()['repository']->get(Field::class);

        $screen = $screenRepository->getByName($clientId, 'Default Screen');

        $summaryField = $fieldRepository->getByCode($clientId, Field::FIELD_SUMMARY_CODE);
        $screenRepository->addData($screen['id'], $summaryField['id'], 1, $currentDate);

        $issueTypeField = $fieldRepository->getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        $screenRepository->addData($screen['id'], $issueTypeField['id'], 2, $currentDate);
        $priorityField = $fieldRepository->getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        $screenRepository->addData($screen['id'], $priorityField['id'], 3, $currentDate);
        $dueDateField = $fieldRepository->getByCode($clientId, Field::FIELD_DUE_DATE_CODE);
        $screenRepository->addData($screen['id'], $dueDateField['id'], 4, $currentDate);
        $componentsField = $fieldRepository->getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        $screenRepository->addData($screen['id'], $componentsField['id'], 5, $currentDate);

        $affectsVersionField = $fieldRepository->getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);
        $screenRepository->addData($screen['id'], $affectsVersionField['id'], 6, $currentDate);
        $fixVersionField = $fieldRepository->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        $screenRepository->addData($screen['id'], $fixVersionField['id'], 7, $currentDate);

        $assigneeField = $fieldRepository->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        $screenRepository->addData($screen['id'], $assigneeField['id'], 8, $currentDate);

        $reporterField = $fieldRepository->getByCode($clientId, Field::FIELD_REPORTER_CODE);
        $screenRepository->addData($screen['id'], $reporterField['id'], 9, $currentDate);
        $environmentField = $fieldRepository->getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);
        $screenRepository->addData($screen['id'], $environmentField['id'], 10, $currentDate);

        $descriptionField = $fieldRepository->getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        $screenRepository->addData($screen['id'], $descriptionField['id'], 11, $currentDate);
        $attachmentField = $fieldRepository->getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        $screenRepository->addData($screen['id'], $attachmentField['id'], 12, $currentDate);

        $timeTrackingField = $fieldRepository->getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        $screenRepository->addData($screen['id'], $timeTrackingField['id'], 13, $currentDate);

        $screen = $screenRepository->getByName($clientId, 'Resolve Issue Screen');
        $assigneeField = $fieldRepository->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        $screenRepository->addData($screen['id'], $assigneeField['id'], 1, $currentDate);

        $fixVersionField = $fieldRepository->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);
        $screenRepository->addData($screen['id'], $fixVersionField['id'], 2, $currentDate);
        $resolutionField = $fieldRepository->getByCode($clientId, Field::FIELD_RESOLUTION_CODE);
        $screenRepository->addData($screen['id'], $resolutionField['id'], 3, $currentDate);

        $commentField = $fieldRepository->getByCode($clientId, Field::FIELD_COMMENT_CODE);
        $screenRepository->addData($screen['id'], $commentField['id'], 4, $currentDate);

        $screen = $screenRepository->getByName($clientId, 'Workflow Screen');
        $assigneeField = $fieldRepository->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        $screenRepository->addData($screen['id'], $assigneeField['id'], 1, $currentDate);
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
        $issueTypes = UbirimiContainer::get()['repository']->get(IssueType::class)->getAll($clientId);
        $query = "INSERT INTO  issue_type_field_configuration_data(issue_type_field_configuration_id, issue_type_id, field_configuration_id, date_created) VALUES ";
        while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
            $query .= "(" . $issueTypeFieldConfigurationId . "," . $issueType['id'] . ", " . $fieldConfigurationId . ", '" . $currentDate . "'), ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $currentDate) {
        $issueTypes = UbirimiContainer::get()['repository']->get(IssueType::class)->getAll($clientId);
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

        $clientData = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getById($clientId);
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        // delete Yongo Product data
        $projects = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjects($clientId);
        while ($projects && $project = $projects->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(YongoProject::class)->deleteById($project['id']);
        }

        $workflows = UbirimiContainer::get()['repository']->get(Workflow::class)->getByClientId($clientId);
        while ($workflows && $workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(Workflow::class)->deleteById($workflow['id']);
        }
        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->deleteByClientId($clientId);

        $screens = UbirimiContainer::get()['repository']->get(Screen::class)->getByClientId($clientId);
        while ($screens && $screen = $screens->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(Screen::class)->deleteById($screen['id']);
        }
        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->deleteByClientId($clientId);

        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteYongoIssueTypes($clientId);
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteYongoIssueStatuses($clientId);
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteYongoIssueResolutions($clientId);
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteYongoIssuePriorities($clientId);
        UbirimiContainer::get()['repository']->get(Field::class)->deleteByClientId($clientId);

        UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->deleteByClientId($clientId);

        UbirimiContainer::get()['repository']->get(FieldConfigurationScheme::class)->deleteByClientId($clientId);

        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->deleteByClientId($clientId);
        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->deleteByClientId($clientId);

        UbirimiContainer::get()['repository']->get(IssueTypeScheme::class)->deleteByClientId($clientId);
        UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class)->deleteByClientId($clientId);

        // delete issue security schemes

        $issueSecuritySchemes = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getByClientId($clientId);
        while ($issueSecuritySchemes && $issueSecurityScheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->deleteById($issueSecurityScheme['id']);
        }

        $users = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getUsers($clientId);

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

        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteGroups($clientId);

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
        $agileBoards = UbirimiContainer::get()['repository']->get(Board::class)->getByClientId($clientId, 'array');
        if ($agileBoards) {
            for ($i = 0; $i < count($agileBoards); $i++) {
                UbirimiContainer::get()['repository']->get(Board::class)->deleteById($agileBoards[$i]['id']);
            }
        }

        // delete Events Product data
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteCalendars($clientId);

        // delete SVN Product data
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteSVNRepositories($clientId);

        // delete Documentador Product data
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->deleteSpaces($clientId);

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
        $defaultScreenData = UbirimiContainer::get()['repository']->get(Screen::class)->getByName($clientId, 'Default Screen');
        $defaultScreenId = $defaultScreenData['id'];

        $query = "INSERT INTO screen_scheme_data(screen_scheme_id, sys_operation_id, screen_id, date_created) VALUES " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_CREATE . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_EDIT . ", " . $defaultScreenId . ", '" . $currentDate . "'), " .
            "(" . $screenSchemeId . "," . SystemOperation::OPERATION_VIEW . ", " . $defaultScreenId . ", '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $currentDate) {
        $issueTypes = UbirimiContainer::get()['repository']->get(IssueType::class)->getAll($clientId);
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
        $workflowRepository = UbirimiContainer::get()['repository']->get(Workflow::class);
        $issueSettingsRepository = UbirimiContainer::get()['repository']->get(IssueSettings::class);

        $screenResolutionData = UbirimiContainer::get()['repository']->get(Screen::class)->getByName($clientId, 'Resolve Issue Screen');
        $screenResolutionId = $screenResolutionData['id'];

        $screenWorkflowData = UbirimiContainer::get()['repository']->get(Screen::class)->getByName($clientId, 'Workflow Screen');
        $screenWorkflowId = $screenWorkflowData['id'];
        $createStepId = $workflowRepository->createDefaultStep($workflowId, null, 'Create Issue', 1);

        $statusOpenIdData = $issueSettingsRepository->getByName($clientId, 'status', 'Open');
        $statusOpenId = $statusOpenIdData['id'];
        $openStepId = $workflowRepository->createDefaultStep($workflowId, $statusOpenId, 'Open', 0);

        $statusInProgressIdData = $issueSettingsRepository->getByName($clientId, 'status', 'In Progress');
        $statusInProgressId = $statusInProgressIdData['id'];

        $inProgressStepId = $workflowRepository->createDefaultStep($workflowId, $statusInProgressId, 'In Progress', 0);

        $statusClosedIdData = $issueSettingsRepository->getByName($clientId, 'status', 'Closed');
        $statusClosedId = $statusClosedIdData['id'];
        $closedStepId = $workflowRepository->createDefaultStep($workflowId, $statusClosedId, 'Closed', 0);

        $statusResolvedIdData = $issueSettingsRepository->getByName($clientId, 'status', 'Resolved');
        $statusResolvedId = $statusResolvedIdData['id'];
        $resolvedStepId = $workflowRepository->createDefaultStep($workflowId, $statusResolvedId, 'Resolved', 0);

        $statusReopenedIdData = $issueSettingsRepository->getByName($clientId, 'status', 'Reopened');
        $statusReopenedId = $statusReopenedIdData['id'];
        $reopenedStepId = $workflowRepository->createDefaultStep($workflowId, $statusReopenedId, 'Reopened', 0);

        $eventIssueWorkStoppedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE, 'id');
        $eventIssueCreatedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE, 'id');
        $eventIssueWorkStartedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE, 'id');
        $eventIssueClosedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CLOSED_CODE, 'id');

        $eventIssueResolvedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_RESOLVED_CODE, 'id');

        $eventIssueReopenedId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_REOPENED_CODE, 'id');

        // create issue -----> open
        $transitionId = $workflowRepository->addTransition($workflowId, null, $createStepId, $openStepId, 'Create Issue', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_CREATE_ISSUE, 'create_issue');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueCreatedId);

        // open ------> in progress
        $transitionId = $workflowRepository->addTransition($workflowId, null, $openStepId, $inProgressStepId, 'Start Progress', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';
        $workflowRepository->addCondition($transitionId, $definitionData);

        // open ------> closed
        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $openStepId, $closedStepId, 'Close Issue', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';
        $workflowRepository->addCondition($transitionId, $definitionData);

        // open ------> resolved

        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $openStepId, $resolvedStepId, 'Resolve Issue', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // in progress ------> open
        $transitionId = $workflowRepository->addTransition($workflowId, null, $inProgressStepId, $openStepId, 'Stop Progress', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStoppedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';
        $workflowRepository->addCondition($transitionId, $definitionData);

        // in progress ------> resolved
        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $inProgressStepId, $resolvedStepId, 'Resolve Issue', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        $workflowRepository->addCondition($transitionId, $definitionData);

        // in progress ------> closed
        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $inProgressStepId, $closedStepId, 'Close Issue', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // resolved ------> closed
        $transitionId = $workflowRepository->addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $closedStepId, 'Close Issue', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);

        $definitionData = '(perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // resolved ------> reopened
        $transitionId = $workflowRepository->addTransition($workflowId, $screenWorkflowId, $resolvedStepId, $reopenedStepId, 'Reopen Issue', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';
        $workflowRepository->addCondition($transitionId, $definitionData);

        // reopened ------> resolved
        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $reopenedStepId, $resolvedStepId, 'Resolve Issue', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueResolvedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // reopened ------> closed
        $transitionId = $workflowRepository->addTransition($workflowId, $screenResolutionId, $reopenedStepId, $closedStepId, 'Close Issue', '');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueClosedId);
        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . '[[AND]]perm_id=' . Permission::PERM_CLOSE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // reopened ------> In progress

        $transitionId = $workflowRepository->addTransition($workflowId, null, $reopenedStepId, $inProgressStepId, 'Start Progress', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueWorkStartedId);

        $definitionData = '(cond_id=' . WorkflowCondition::CONDITION_ONLY_ASSIGNEE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

        // closed ------> reopened
        $transitionId = $workflowRepository->addTransition($workflowId, $screenWorkflowId, $closedStepId, $reopenedStepId, 'Reopen Issue', '');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE, 'field_name=resolution###field_value=-1');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP, 'set_issue_status');
        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY, 'update_issue_history');

        $workflowRepository->addPostFunctionToTransition($transitionId, WorkflowFunction::FUNCTION_FIRE_EVENT, 'event=' . $eventIssueReopenedId);

        $definitionData = '(perm_id=' . Permission::PERM_RESOLVE_ISSUE . ')';

        $workflowRepository->addCondition($transitionId, $definitionData);

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

    public function getDocumentadorSettings($clientId) {
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
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_AFFECTS_VERSION_CODE);

        $fieldConfigurationRepository = UbirimiContainer::get()['repository']->get(FieldConfiguration::class);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_ASSIGNEE_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_ATTACHMENT_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_COMMENT_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_COMPONENT_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_DESCRIPTION_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_DUE_DATE_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_ENVIRONMENT_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_FIX_VERSION_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_ISSUE_TYPE_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_PRIORITY_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_REPORTER_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_RESOLUTION_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');

        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_SUMMARY_CODE);

        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 1, '');
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getByCode($clientId, Field::FIELD_ISSUE_TIME_TRACKING_CODE);
        $fieldConfigurationRepository->addCompleteData($fieldConfigurationId, $field['id'], 1, 0, '');
    }

    public function addDefaultDocumentadorGlobalPermissionData($clientId) {
        $groupAdministrators = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Administrators');
        $groupUsers = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Users');

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
        $clientRepository = UbirimiContainer::get()['repository']->get(UbirimiClient::class);

        // set default YONGO Product Settings
        $clientRepository->createDefaultYongoSettings($clientId);

        // add default issue priorities, statuses, resolutions
        $clientRepository->createDefaultIssuePriorities($clientId, $clientCreatedDate);
        $clientRepository->createDefaultIssueStatuses($clientId, $clientCreatedDate);
        $clientRepository->createDefaultIssueResolutions($clientId, $clientCreatedDate);

        // create default Screens
        $clientRepository->createDefaultScreens($clientId, $clientCreatedDate);

        $screenSchemeId = $clientRepository->createDefaultScreenScheme($clientId, $clientCreatedDate);
        $clientRepository->createDefaultScreenSchemeData($clientId, $screenSchemeId, $clientCreatedDate);

        // create default issue types
        $clientRepository->createDefaultIssueTypes($clientId, $clientCreatedDate);
        $issueTypeSchemeId = $clientRepository->createDefaultIssueTypeScheme($clientId, 'project', $clientCreatedDate);
        $clientRepository->createDefaultIssueTypeSchemeData($clientId, $issueTypeSchemeId, $clientCreatedDate);

        // create default workflow issue type scheme
        $workflowIssueTypeSchemeId = $clientRepository->createDefaultIssueTypeScheme($clientId, 'workflow', $clientCreatedDate);
        $clientRepository->createDefaultIssueTypeSchemeData($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);

        // create default issue type screen scheme
        $issueTypeScreenSchemeId = $clientRepository->createDefaultIssueTypeScreenScheme($clientId, $clientCreatedDate);
        $clientRepository->createDefaultIssueTypeScreenSchemeData($clientId, $issueTypeScreenSchemeId, $screenSchemeId, $clientCreatedDate);

        // create default events
        $clientRepository->createDefaultEvents($clientId, $clientCreatedDate);

        // create default workflow
        $workflowId = $clientRepository->createDefaultWorkflow($clientId, $workflowIssueTypeSchemeId, $clientCreatedDate);
        $clientRepository->createDefaultWorkflowData($clientId, $workflowId, $clientCreatedDate);

        // create default workflow scheme
        $workflowSchemeId = $clientRepository->createDefaultWorkflowScheme($clientId, $clientCreatedDate);
        $clientRepository->createDefaultWorkflowSchemeData($workflowSchemeId, $workflowId, $clientCreatedDate);

        // create Default Fields
        $clientRepository->createDefaultFields($clientId, $clientCreatedDate);

        // create default link issue options
        $clientRepository->createDefaultLinkIssueOptions($clientId, $clientCreatedDate);

        // create default field configurations
        $fieldConfigurationId = $clientRepository->createDefaultFieldConfiguration($clientId, $clientCreatedDate);
        $clientRepository->createDefaultFieldConfigurationData($clientId, $fieldConfigurationId, $clientCreatedDate);
        $issueTypeFieldConfigurationId = $clientRepository->createDefaultIssueTypeFieldConfiguration($clientId, $clientCreatedDate);
        $clientRepository->createDefaultIssueTypeFieldConfigurationData($clientId, $issueTypeFieldConfigurationId, $fieldConfigurationId, $clientCreatedDate);

        $clientRepository->createDefaultScreenData($clientId, $clientCreatedDate);

        // create default permission roles
        UbirimiContainer::get()['repository']->getRepository(Role::class)->addDefaultPermissionRoles($clientId, $clientCreatedDate);

        // create default group names
        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addDefaultYongoGroups($clientId, $clientCreatedDate);

        $roleAdministrators = UbirimiContainer::get()['repository']->getRepository(Role::class)->getByName($clientId, 'Administrators');

        $roleDevelopers = UbirimiContainer::get()['repository']->getRepository(Role::class)->getByName($clientId, 'Developers');
        $roleUsers = UbirimiContainer::get()['repository']->getRepository(Role::class)->getByName($clientId, 'Users');

        $groupAdministrators = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Administrators');

        $groupDevelopers = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Developers');
        $groupUsers = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Users');

        UbirimiContainer::get()['repository']->getRepository(Role::class)->addDefaultGroups($roleAdministrators['id'], array($groupAdministrators['id']), $clientCreatedDate);
        UbirimiContainer::get()['repository']->getRepository(Role::class)->addDefaultGroups($roleDevelopers['id'], array($groupDevelopers['id']), $clientCreatedDate);

        UbirimiContainer::get()['repository']->getRepository(Role::class)->addDefaultGroups($roleUsers['id'], array($groupUsers['id']), $clientCreatedDate);

        // add in Administrators group the current user
        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addData($groupDevelopers['id'], array($userId), $clientCreatedDate);
        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addData($groupUsers['id'], array($userId), $clientCreatedDate);

        // create default permission scheme
        $permissionSchemeId = $clientRepository->createDefaultPermissionScheme($clientId, $clientCreatedDate);

        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->addDefaultPermissions($permissionSchemeId, $roleAdministrators['id'], $roleDevelopers['id'], $roleUsers['id'], $clientCreatedDate);

        // create default notification scheme
        $notificationSchemeId = $clientRepository->createDefaultNotificationScheme($clientId, $clientCreatedDate);
        UbirimiContainer::get()['repository']->get(NotificationScheme::class)->addDefaultNotifications($clientId, $notificationSchemeId);

        // add global permission
        $clientRepository->addYongoGlobalPermissionData($clientId, $groupAdministrators, $groupUsers);
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
        $linkTypeRepository = UbirimiContainer::get()['repository']->get(IssueLinkType::class);
        $linkTypeRepository->add($clientId, 'Relates', 'relates to', 'relates to', $currentDate);
        $linkTypeRepository->add($clientId, 'Duplicate', 'duplicates', 'is duplicated by', $currentDate);
        $linkTypeRepository->add($clientId, 'Blocks', 'blocks', 'is blocked by', $currentDate);
        $linkTypeRepository->add($clientId, 'Cloners', 'clones', 'is cloned by', $currentDate);
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
        $clientRepository = UbirimiContainer::get()['repository']->get(UbirimiClient::class);
        $clientData = $clientRepository->getById($clientId);
        $userData = $clientRepository->getUsers($clientId);
        $user = $userData->fetch_array(MYSQLI_ASSOC);
        $userId = $user['id'];

        $clientCreatedDate = $clientData['date_created'];

        $clientRepository->installYongoProduct($clientId, $userId, $clientCreatedDate);
        $clientRepository->installDocumentadorProduct($clientId, $userId, $clientCreatedDate);
        $clientRepository->installCalendarProduct($clientId, $userId, $clientCreatedDate);

        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_YONGO, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_AGILE, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_SVN_HOSTING, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_HELP_DESK, $clientCreatedDate);
        $clientRepository->addProduct($clientId, SystemProduct::SYS_PRODUCT_QUICK_NOTES, $clientCreatedDate);

        UbirimiContainer::get()['repository']->get(SMTPServer::class)->add(
            $clientId,
            'Ubirimi Mail Server',
            'The default Ubirimi mail server',
            UbirimiContainer::get()['smtp.from_email_address'],
            'UBR',
            SMTPServer::PROTOCOL_SECURE_SMTP,
            UbirimiContainer::get()['smtp.hostname'],
            UbirimiContainer::get()['smtp.port'],
            UbirimiContainer::get()['smtp.timeout'],
            UbirimiContainer::get()['smtp.tls'],
            UbirimiContainer::get()['smtp.username'],
            UbirimiContainer::get()['smtp.password'],
            1,
            $clientCreatedDate
        );

        $clientRepository->setInstalledFlag($clientId, 1);
    }

    public function addDefaultDocumentadorUserGroups($clientId, $date) {
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

    public function addDefaultDocumentadorSettings($clientId) {
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
        $calendars = UbirimiContainer::get()['repository']->get(UbirimiCalendar::class)->getByClientId($clientId);
        if ($calendars) {
            while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)) {

                UbirimiContainer::get()['repository']->get(UbirimiCalendar::class)->deleteById($calendar['id']);
            }
        }
    }

    public function deleteSVNRepositories($clientId) {
        $repositories = UbirimiContainer::get()['repository']->get(SvnRepository::class)->getAllByClientId($clientId);
        if ($repositories) {
            while ($repository = $repositories->fetch_array(MYSQLI_ASSOC)) {
                UbirimiContainer::get()['repository']->get(SvnRepository::class)->deleteAllById($repository['id']);
            }
        }
    }

    public function deleteSpaces($clientId) {
        $spaces = UbirimiContainer::get()['repository']->get(Space::class)->getByClientId($clientId);
        if ($spaces) {
            while ($space = $spaces->fetch_array(MYSQLI_ASSOC)) {
                UbirimiContainer::get()['repository']->get(Space::class)->deleteById($space['space_id']);
            }
        }
    }

    public function installDocumentadorProduct($clientId, $userId, $clientCreatedDate) {
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->addDefaultDocumentadorUserGroups($clientId, $clientCreatedDate);

        $groupAdministrators = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Administrators');
        $groupUsers = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Users');

        // add in Administrators/Users groups the current user
        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addData($groupAdministrators['id'], array($userId), $clientCreatedDate);

        UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->addData($groupUsers['id'], array($userId), $clientCreatedDate);

        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->addDefaultDocumentadorSettings($clientId);
        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->addDefaultDocumentadorGlobalPermissionData($clientId);
    }

    public function installCalendarProduct($clientId, $userId, $clientCreatedDate) {

        // create default calendar for the first user
        $userData = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($userId);

        $calendarId = UbirimiContainer::get()['repository']->get(UbirimiCalendar::class)->save($userData['id'], $userData['first_name'] . ' ' . $userData['last_name'], 'My default calendar', '#A1FF9E', $clientCreatedDate, 1);

        // add default reminders
        UbirimiContainer::get()['repository']->get(UbirimiCalendar::class)->addReminder($calendarId, \Ubirimi\Calendar\Repository\Reminder\ReminderType::REMINDER_EMAIL, ReminderPeriod::PERIOD_MINUTE, 30);
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
