<?php

namespace Ubirimi\Yongo\Repository\Notification;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;

class NotificationScheme {

    private $name;
    private $description;
    private $clientId;

    function __construct($clientId, $name, $description) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public static function getAll() {
        $query = "select * " .
                 "from notification_scheme ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function save($currentDate) {
        $query = "INSERT INTO notification_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getByClientId($clientId) {
        $query = "select * " .
            "from notification_scheme " .
            "where client_id = ? ";

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
            "from notification_scheme " .
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

    public static function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from notification_scheme where client_id = ? and LOWER(name) = ?";
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

    public static function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update notification_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("sssi", $name, $description, $date, $Id);
            $stmt->execute();
        }
    }

    public static function addDataRaw($notificationSchemeId, $eventId, $permissionRoleId, $groupId, $userId, $currentAssignee, $reporter, $currentUser, $projectLead, $componentLead, $currentDate) {
        $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, permission_role_id, group_id, user_id, current_assignee, reporter, " .
                    "`current_user`, project_lead, component_lead, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiiiiiiiiis", $notificationSchemeId, $eventId, $permissionRoleId, $groupId, $userId, $currentAssignee, $reporter, $currentUser, $projectLead, $componentLead, $currentDate);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        } else echo UbirimiContainer::get()['db.connection']->error;
    }

    public static function addData($notificationSchemeId, $eventId, $notificationType, $user, $group, $role, $currentDate) {

        switch ($notificationType) {

            case Notification::NOTIFICATION_TYPE_USER:
                $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, user_id, date_created) VALUES (?, ?, ?, ?)";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                    $stmt->bind_param("iiis", $notificationSchemeId, $eventId, $user, $currentDate);
                    $stmt->execute();
                    return UbirimiContainer::get()['db.connection']->insert_id;
                }

                break;

            case Notification::NOTIFICATION_TYPE_GROUP:
                $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, group_id, date_created) VALUES (?, ?, ?, ?)";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                    $stmt->bind_param("iiis", $notificationSchemeId, $eventId, $group, $currentDate);
                    $stmt->execute();
                    return UbirimiContainer::get()['db.connection']->insert_id;
                }

                break;

            case Notification::NOTIFICATION_TYPE_PROJECT_ROLE:
                $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, permission_role_id, date_created) VALUES (?, ?, ?, ?)";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                    $stmt->bind_param("iiis", $notificationSchemeId, $eventId, $role, $currentDate);
                    $stmt->execute();
                    return UbirimiContainer::get()['db.connection']->insert_id;
                }

                break;

            case Notification::NOTIFICATION_TYPE_CURRENT_ASSIGNEE:
            case Notification::NOTIFICATION_TYPE_REPORTER:
            case Notification::NOTIFICATION_TYPE_CURRENT_USER:
            case Notification::NOTIFICATION_TYPE_PROJECT_LEAD:
            case Notification::NOTIFICATION_TYPE_COMPONENT_LEAD:
                $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, `" . $notificationType . "`, date_created) VALUES (?, ?, ?, ?)";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    $value = 1;
                    $stmt->bind_param("iiis", $notificationSchemeId, $eventId, $value, $currentDate);
                    $stmt->execute();
                    return UbirimiContainer::get()['db.connection']->insert_id;
                }

                break;
        }
    }

    public static function getDataByNotificationSchemeId($notificationSchemeId) {
        $query = "select notification_scheme_data.* " .
                     "from notification_scheme_data " .
                     "where notification_scheme_data.notification_scheme_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $notificationSchemeId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getDataByNotificationSchemeIdAndEventId($notificationSchemeId, $eventId) {
        $query = "select notification_scheme_data.id, user.first_name, user.last_name, user.id as user_id, group.id as group_id, group.name as group_name, notification_scheme_data.current_assignee, notification_scheme_data.reporter,  " .
            "notification_scheme_data.current_user, notification_scheme_data.permission_role_id, notification_scheme_data.project_lead, notification_scheme_data.component_lead, permission_role.name as role_name, " .
            "event.id as event_id, event.name as event_name " .
            "from notification_scheme_data " .
            "left join event on event.id = notification_scheme_data.event_id " .
            "left join user on user.id = notification_scheme_data.user_id " .
            "left join `group` on `group`.id = notification_scheme_data.group_id " .
            "left join permission_role on permission_role.id = notification_scheme_data.permission_role_id " .
            "where notification_scheme_data.notification_scheme_id = ? and " .
                "notification_scheme_data.event_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $notificationSchemeId, $eventId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteDataById($notificationSchemeDataId) {
        $query = "delete from notification_scheme_data where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $notificationSchemeDataId);
            $stmt->execute();
        }
    }

    public static function deleteDataByNotificationSchemeId($notificationSchemeId) {
        $query = "delete from notification_scheme_data where notification_scheme_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $notificationSchemeId);
            $stmt->execute();
        }
    }

    public static function addDefaultNotifications($clientId, $notificationSchemeId) {

        $eventCreatedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE, 'id');

        $eventUpdatedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_UPDATED_CODE, 'id');
        $eventAssignedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_ASSIGNED_CODE, 'id');

        $eventResolvedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_RESOLVED_CODE, 'id');

        $eventClosedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CLOSED_CODE, 'id');
        $eventCommentedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_COMMENTED_CODE, 'id');

        $eventCommentEditedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_COMMENT_EDITED_CODE, 'id');
        $eventReopenedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_REOPENED_CODE, 'id');
        $eventWorkStartedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE, 'id');

        $eventWorkStoppedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE, 'id');
        $eventDeletedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_DELETED_CODE, 'id');

        $eventGenericId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_GENERIC_CODE, 'id');

        $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, reporter) VALUES " .
            "(" . $notificationSchemeId. "," . $eventCreatedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventUpdatedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventAssignedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventResolvedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventClosedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventCommentedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventCommentEditedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventReopenedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventDeletedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventWorkStartedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventGenericId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventWorkStoppedId . ', 1)';

        UbirimiContainer::get()['db.connection']->query($query);

        $query = "INSERT INTO notification_scheme_data(notification_scheme_id, event_id, current_assignee) VALUES " .
            "(" . $notificationSchemeId. "," . $eventCreatedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventUpdatedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventAssignedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventResolvedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventClosedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventCommentedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventCommentEditedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventReopenedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventDeletedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventWorkStartedId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventGenericId . ', 1),' .
            "(" . $notificationSchemeId. "," . $eventWorkStoppedId . ', 1)';

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteById($notificationSchemeId) {
        $query = "delete from notification_scheme where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $notificationSchemeId);
            $stmt->execute();
        }
    }

    public static function deleteByClientId($clientId) {
        $notificationSchemes = NotificationScheme::getByClientId($clientId);
        while ($notificationSchemes && $notificationScheme = $notificationSchemes->fetch_array(MYSQLI_ASSOC)) {
            NotificationScheme::deleteDataByNotificationSchemeId($notificationScheme['id']);
            NotificationScheme::deleteById($notificationScheme['id']);
        }
    }
}