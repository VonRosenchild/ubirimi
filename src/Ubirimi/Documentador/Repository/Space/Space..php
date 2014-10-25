<?php

namespace Ubirimi\Documentador\Repository\Space;

use Ubirimi\Container\UbirimiContainer;

use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\Documentador\Repository\Entity\EntityComment;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class Space {

    public $name;
    public $code;
    public $description;
    public $clientId;
    public $userCreatedId;

    function __construct($clientId = null, $userCreatedId = null, $name = null, $code = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->userCreatedId = $userCreatedId;

        return $this;
    }

    public function setHomePageId($spaceId, $pageId) {
        $query = "update documentator_space set home_entity_id = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $pageId, $spaceId);
            $stmt->execute();
        }
    }

    public function getFirstSpace($clientId) {
        $query = "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, " .
                    "documentator_space.description, documentator_space.date_created, documentator_space.user_created_id " .
                    "FROM documentator_space " .
                    "where documentator_space.client_id = ? " .
                    "order by documentator_space.id asc " .
                    "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function save($currentDate) {
        $query = "INSERT INTO documentator_space(client_id, user_created_id, name, code, description, date_created) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iissss", $this->clientId, $this->userCreatedId, $this->name, $this->code, $this->description, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getAllByClientId($clientId, $resultType = null, $resultColumn = null, $filters = null) {
        $query = "SELECT * " .
            "FROM documentator_space " .
            "where documentator_space.client_id = ?";

        if (empty($filters['sort_by'])) {
            $query .= ' order by documentator_space.id';
        }
        else {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
    }

    public function getAll($filters = null) {
        $query = "SELECT * " .
            "FROM documentator_space ";

        if (empty($filters['sort_by'])) {
            $query .= ' order by documentator_space.id';
        }
        else {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result;
            } else
                return null;
        }
    }

    public function getAllForAllClients() {
        $query = "SELECT * " .
            "FROM documentator_space";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result;
            } else
                return null;
        }
    }

    public function getByClientId($clientId, $favouriteFlag = null) {
        $query = "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, documentator_space.description, " .
                 "documentator_space.date_created, documentator_space.user_created_id, documentator_space.home_entity_id, " .
                 "user.id as user_id, user.first_name, user.last_name " .
                 "FROM documentator_space " .
                 "left join user on user.id = documentator_space.user_created_id ";
        if ($favouriteFlag)
            $query .= 'left join documentator_user_space_favourite on documentator_user_space_favourite.space_id = documentator_space.id ';

        $query .= " where documentator_space.client_id = ?";

        if ($favouriteFlag)
            $query .= ' and documentator_user_space_favourite.id is not null';

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

    public function getByClientIdAndAnonymous($clientId) {
        $query = "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, documentator_space.description, " .
                 "documentator_space.date_created, documentator_space.user_created_id, documentator_space.home_entity_id, " .
                 "user.id as user_id, user.first_name, user.last_name " .
                 "FROM documentator_space " .
                 "left join user on user.id = documentator_space.user_created_id " .
                 "left join documentator_space_permission_anonymous on documentator_space_permission_anonymous.documentator_space_id = documentator_space.id " .
                 "where documentator_space.client_id = ? and " .
                 "documentator_space_permission_anonymous.all_view_flag = 1";

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

    public function getById($Id) {
        $query = "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, documentator_space.description, " .
                 "documentator_space.date_created, documentator_space.user_created_id, documentator_space.home_entity_id, documentator_entity.name as home_page_name, " .
                 "user.id as user_id, user.first_name, user.last_name, documentator_space.client_id, " .
                 "documentator_entity.in_trash_flag " .
                 "FROM documentator_space " .
                 "left join user on user.id = documentator_space.user_created_id " .
                 "left join documentator_entity on documentator_entity.id = documentator_space.home_entity_id " .
                 "where documentator_space.id = ? " .
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

    public function updateById($spaceId, $name, $code, $homepageId, $description, $date) {
        $query = "update documentator_space set name = ?, code = ?, home_entity_id = ?, description = ?, date_updated = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ssissi", $name, $code, $homepageId, $description, $date, $spaceId);
            $stmt->execute();
        }
    }

    public function deletePermissionsBySpaceId($spaceId) {
        $query = "delete from documentator_space_permission where space_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
        }
    }

    public function deleteById($spaceId) {
        $spaceEntities = UbirimiContainer::get()['repository']->get(Entity::class)->getAllBySpaceId($spaceId);
        if ($spaceEntities) {
            while ($spaceEntity = $spaceEntities->fetch_array(MYSQLI_ASSOC)) {
                UbirimiContainer::get()['repository']->get(EntityComment::class)->deleteCommentsByEntityId($spaceEntity['id']);
                UbirimiContainer::get()['repository']->get(Entity::class)->removeAsFavouriteForUsers($spaceEntity['id']);
                UbirimiContainer::get()['repository']->get(Entity::class)->deleteRevisionsByEntityId($spaceEntity['id']);

                UbirimiContainer::get()['repository']->get(Entity::class)->deleteFilesByEntityId($spaceEntity['id']);
                UbirimiContainer::get()['repository']->get(EntityAttachment::class)->deleteByEntityId($spaceEntity['id'], $spaceId);
                UbirimiContainer::get()['repository']->get(Entity::class)->deleteById($spaceEntity['id']);

                // delete any files, if any
                $spaceBasePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
                Util::deleteDir($spaceBasePath . $spaceEntity['id']);
            }
        }

        UbirimiContainer::get()['repository']->get(Space::class)->deletePermissionsBySpaceId($spaceId);

        $query = "delete from documentator_space where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
        }

        $spaceBasePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
        Util::deleteDir($spaceBasePath . $spaceId);
    }

    public function getByCodeAndClientId($clientId, $code) {
        $query = "SELECT id " .
            "FROM documentator_space " .
            "where documentator_space.client_id = ? and code = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("is", $clientId, $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function getByNameAndClientId($clientId, $name) {
        $query = "SELECT id " .
            "FROM documentator_space " .
            "where documentator_space.client_id = ? and name = ?";

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

    public function getChildrenPagesBySpaceIdAndPageId($spaceId, $pageId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, " .
            "documentator_entity.date_created, documentator_entity.content, page_child.id as child_id, documentator_entity.parent_entity_id, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "left join documentator_entity page_child on page_child.parent_entity_id = documentator_entity.id " .
            "where documentator_entity.documentator_space_id = ? and " .
            "documentator_entity.in_trash_flag = 0 and " .
            "(documentator_entity.parent_entity_id = ? OR documentator_entity.parent_entity_id = 0) and " .
            "documentator_entity.in_trash_flag = 0 " .
            "group by documentator_entity.id";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $spaceId, $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function getAllBySpaceIdNoExistingParent($spaceId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, " .
            "documentator_entity.date_created, documentator_entity.content, documentator_entity.parent_entity_id, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "left join documentator_entity parent_page on parent_page.id = documentator_entity.parent_entity_id " .
            "where documentator_entity.documentator_space_id = ? and " .
            "documentator_entity.in_trash_flag = 0 and " .
            "parent_page.id is null " .
            "group by documentator_entity.id";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function getAnonymousAccessSettings($spaceId) {
        $query = "SELECT * " .
            "FROM documentator_space_permission_anonymous " .
            "where documentator_space_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function updatePermissions($spaceId, $groupId = null, $userId = null, $parameters) {

        $query = 'UPDATE documentator_space_permission SET ';

        $values = array();
        $values_ref = array();
        $valuesType = '';
        for ($i = 0; $i < count($parameters); $i++) {
            $query .= $parameters[$i]['field'] .= ' = ?, ';
            $values[] = $parameters[$i]['value'];
            $valuesType .= $parameters[$i]['type'];
        }

        $query = substr($query, 0, strlen($query) - 2) . ' ' ;

        if ($groupId) {
            $query .= 'WHERE group_id = ? ';
            $values[] = $groupId;
            $valuesType .= 'i';
        } else if ($userId) {
            $query .= 'WHERE user_id = ? ';
            $values[] = $userId;
            $valuesType .= 'i';
        }

        $query .= 'and space_id = ? ';
        $values[] = $spaceId;
        $valuesType .= 'i';
        $query .= 'LIMIT 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            foreach ($values as $key => $value)
                $values_ref[$key] = &$values[$key];

            if ($valuesType != '')
                call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
            $stmt->execute();
            $result = $stmt->get_result();
        }
    }
    public function updatePermissionsAnonymous($spaceId, $parameters) {

        // delete all the anonymous permissions
        $query = "delete from documentator_space_permission_anonymous where documentator_space_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
        }

        // insert the permissions
        $query = 'insert into documentator_space_permission_anonymous(documentator_space_id, ';

        for ($i = 0; $i < count($parameters); $i++) {
            $query .= $parameters[$i]['field'] . ', ';
        }

        $query = substr($query, 0, strlen($query) - 2) . ') value (?, ' ;

        $values = array();
        $values[] = $spaceId;
        $values_ref = array();
        $valuesType = 'i';
        for ($i = 0; $i < count($parameters); $i++) {
            $query .= '?, ';
            $values[] = $parameters[$i]['value'];
            $valuesType .= $parameters[$i]['type'];
        }

        $query = substr($query, 0, strlen($query) - 2) . ') ' ;

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            foreach ($values as $key => $value)
                $values_ref[$key] = &$values[$key];

            if ($valuesType != '')
                call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
            $stmt->execute();
        }
    }

    public function setDefaultPermissions($clientId, $spaceId) {

        $groupAdministrators = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Administrators');
        $groupUsers = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByName($clientId, 'Documentador Users');

        if ($groupAdministrators) {
            $groupAdministratorsId = $groupAdministrators['id'];
            $query = "INSERT INTO documentator_space_permission(space_id, group_id, all_view_flag, space_admin_flag) VALUES (?, ?, 1, 1)";
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("ii", $spaceId, $groupAdministratorsId);
                $stmt->execute();
            }
        }

        if ($groupUsers) {
            $groupUsersId = $groupUsers['id'];
            $query = "INSERT INTO documentator_space_permission(space_id, group_id, all_view_flag) VALUES (?, ?, 1)";
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("ii", $spaceId, $groupUsersId);
                $stmt->execute();
            }
        }
    }

    public function hasAnonymousAccess($spaceId) {
        $query = "SELECT documentator_space.id " .
            "FROM documentator_space " .
            "left join documentator_space_permission_anonymous on documentator_space_permission_anonymous.documentator_space_id = documentator_space.id " .
            "where documentator_space.id = ? and " .
            "documentator_space_permission_anonymous.all_view_flag = 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return true;
            else
                return null;
        }
    }

    public function getDeletedPages($spaceId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "where documentator_entity.documentator_space_id = ? and " .
            "documentator_entity.in_trash_flag = 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function deleteAllFromTrash($spaceId) {
        $entities = UbirimiContainer::get()['repository']->get(Space::class)->getDeletedPages($spaceId);

        if ($entities) {
            while ($entity = $entities->fetch_array(MYSQLI_ASSOC)) {
                EntityComment::deleteCommentsByEntityId($entity['id']);
                Entity::removeAsFavouriteForUsers($entity['id']);
                UbirimiContainer::get()['repository']->get(Entity::class)->deleteRevisionsByEntityId($entity['id']);
                UbirimiContainer::get()['repository']->get(Entity::class)->deleteFilesByEntityId($entity['id']);
                EntityAttachment::deleteByEntityId($entity['id'], $spaceId);

                UbirimiContainer::get()['repository']->get(Entity::class)->deleteById($entity['id']);
            }
        }
    }

    public function checkSpaceIsFavouriteForUserId($spaceId, $userId) {
        $query = "SELECT documentator_user_space_favourite.id " .
            "FROM documentator_user_space_favourite " .
            "where documentator_user_space_favourite.user_id = ? and " .
            "documentator_user_space_favourite.space_id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $userId, $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function addToFavourites($spaceId, $userId, $currentDate) {
        $query = "INSERT INTO documentator_user_space_favourite(space_id, user_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $spaceId, $userId, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function removeFavourite($spaceId, $userId) {
        $query = "delete from documentator_user_space_favourite where space_id = ? and user_id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $spaceId, $userId);
            $stmt->execute();
        }
    }

    public function searchForPages($clientId, $searchQuery) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "where (documentator_entity.name like '%" . $searchQuery . "%' OR documentator_entity.content like '%" . $searchQuery . "%') " .
            "AND user.client_id = ?";

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

    public function getGroupPermission($spaceId, $groupId) {
        $query = "SELECT * from documentator_space_permission where group_id = ? and space_id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $groupId, $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function getUserPermission($spaceId, $userId) {
        $query = "SELECT * from documentator_space_permission where user_id = ? and space_id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $userId, $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function getUsersWithPermissions($spaceId) {
        $query = "SELECT user.id as user_id, user.first_name, user.last_name, " .
                 "documentator_space_permission.id, documentator_space_permission.all_view_flag, documentator_space_permission.space_admin_flag " .
                 "from documentator_space_permission " .
                 "left join user on user.id = documentator_space_permission.user_id " .
                 "where space_id = ? and " .
                 "user.id is not null";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function getGroupsWithPermissions($spaceId) {
        $query = "SELECT group.id as group_id, group.name, " .
                 "documentator_space_permission.id, documentator_space_permission.all_view_flag, documentator_space_permission.space_admin_flag " .
                 "from documentator_space_permission " .
                 "left join `group` on `group`.id = documentator_space_permission.group_id " .
                 "where space_id = ? and " .
                 "group.id is not null";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function removePermissionsForAllGroups($spaceId) {
        $query = "delete from documentator_space_permission where space_id = ? and group_id is not null";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
        }
    }

    public function removePermissionsForAllUsers($spaceId) {
        $query = "delete from documentator_space_permission where space_id = ? and user_id is not null";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $spaceId);
            $stmt->execute();
        }
    }

    public function updateGroupPermissions($spaceId, $groupPermissions) {
        foreach ($groupPermissions as $groupId => $data) {

            $query = "INSERT INTO documentator_space_permission(space_id, group_id, " . implode(', ', array_keys($data)) . ') values (' . $spaceId . ', ' . $groupId . ', ';
            $auxValuePlaceholder = array();
            for ($i = 0; $i < count(array_keys($data)); $i++)
                $auxValuePlaceholder[] = 1;
            $query .= implode(', ', $auxValuePlaceholder);

            $query .= ');';
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
            }
        }
    }

    public function updateUserPermissions($spaceId, $userPermissions) {
        foreach ($userPermissions as $userId => $data) {

            $query = "INSERT INTO documentator_space_permission(space_id, user_id, " . implode(', ', array_keys($data)) . ') values (' . $spaceId . ', ' . $userId . ', ';
            $auxValuePlaceholder = array();
            for ($i = 0; $i < count(array_keys($data)); $i++)
                $auxValuePlaceholder[] = 1;
            $query .= implode(', ', $auxValuePlaceholder);

            $query .= ');';
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
            }
        }
    }

    public function addUserAllPermissions($spaceId, $userId) {
        $query = "INSERT INTO documentator_space_permission(space_id, user_id, all_view_flag, space_admin_flag) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $allViewFlag = 1;
            $spaceAdminFlag = 1;
            $stmt->bind_param("iiii", $spaceId, $userId, $allViewFlag, $spaceAdminFlag);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function userHasAdminSpacePermission($spaceId, $userId) {
        $query = "SELECT * from documentator_space_permission where user_id = ? and space_id = ? and space_admin_flag = 1 limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $userId, $spaceId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function getWithAdminPermissionByUserId($clientId, $userId) {
        $query = "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, documentator_space.description, " .
                        "documentator_space.date_created, documentator_space.user_created_id, documentator_space.home_entity_id, " .
                        "user.id as user_id, user.first_name, user.last_name " .
                 "from documentator_space_permission " .
                 "left join documentator_space on documentator_space.id = documentator_space_permission.space_id " .
                 "left join user on user.id = documentator_space_permission.user_id " .
                 "where documentator_space.client_id = ? and " .
                 "documentator_space_permission.user_id = ? and " .
                 "space_admin_flag = 1 " .

                 "UNION " .

                 "SELECT documentator_space.id as space_id, documentator_space.name, documentator_space.code, documentator_space.description, " .
                         "documentator_space.date_created, documentator_space.user_created_id, documentator_space.home_entity_id, " .
                         "user.id as user_id, user.first_name, user.last_name " .
                 "from documentator_space_permission " .
                 "left join documentator_space on documentator_space.id = documentator_space_permission.space_id " .
                 "left join `group` on `group`.id = documentator_space_permission.group_id " .
                 "left join `group_data` on `group_data`.group_id = `group`.id " .
                 "left join user on user.id = group_data.user_id " .
                 "where documentator_space.client_id = ? and " .
                 "user.id = ? and " .
                 "space_admin_flag = 1 ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iiii", $clientId, $userId, $clientId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }
}