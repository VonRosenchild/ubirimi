<?php

namespace Ubirimi\Repository\Documentador;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\Documentador\EntityAttachment;
use Ubirimi\Repository\Documentador\EntityComment;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class Entity {

    public $spaceId;
    public $pageParentId;
    public $entityTypeId;
    public $userCreatedId;
    public $parentPageId;
    public $name;
    public $content;

    function __construct($entityTypeId, $spaceId, $userCreatedId, $parentPageId, $name, $content) {
        $this->entityTypeId = $entityTypeId;
        $this->spaceId = $spaceId;
        $this->name = $name;
        $this->content = $content;
        $this->userCreatedId = $userCreatedId;
        $this->parentPageId = $parentPageId;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO documentator_entity(documentator_entity_type_id, documentator_space_id, parent_entity_id, user_created_id, name, content, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiiisss", $this->entityTypeId, $this->spaceId, $this->parentPageId, $this->userCreatedId, $this->name, $this->content, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getAllBySpaceId($spaceId, $inTrashFlag = null) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
                 "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "where documentator_entity.documentator_space_id = ? ";

        if (isset($inTrashFlag))
            $query .= " and documentator_entity.in_trash_flag = " . $inTrashFlag;

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

    public static function getById($pageId, $userId = null, $inTrashFlag = null) {
        $query = "SELECT documentator_entity.name, documentator_entity.documentator_entity_type_id, documentator_entity.id, " .
                 "documentator_entity.date_created, documentator_entity.content, in_trash_flag, documentator_entity.parent_entity_id, " .
                 "documentator_entity.documentator_space_id as space_id, " .
                 "user.id as user_id, user.first_name, user.last_name, " .
                 "documentator_space.name as space_name ";

        if ($userId)
            $query .= ", documentator_user_entity_favourite.id as fav_id ";

        $query .= "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "left join documentator_space on documentator_space.id = documentator_entity.documentator_space_id ";

        if ($userId)
            $query .= "left join documentator_user_entity_favourite on (documentator_user_entity_favourite.entity_id = ? and documentator_user_entity_favourite.user_id = ?) ";

        $query .= "where documentator_entity.id = ? ";

        if (isset($inTrashFlag))
            $query .= ' and documentator_entity.in_trash_flag = ' . $inTrashFlag;

        $query .= " limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            if ($userId)
                $stmt->bind_param("iii", $pageId, $userId, $pageId);
            else
                $stmt->bind_param("i", $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function updateById($pageId, $name, $content, $date) {
        $query = "update documentator_entity set name = ?, content = ?, date_updated = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("sssi", $name, $content, $date, $pageId);
            $stmt->execute();
        }
    }

    public static function getFavouritePagesByClientIdAndUserId($clientId, $userId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "left join documentator_space on documentator_space.id = documentator_entity.documentator_space_id " .
            "left join documentator_user_entity_favourite on (documentator_user_entity_favourite.entity_id = documentator_entity.id) " .
            "where documentator_user_entity_favourite.user_id = ? and " .
            "documentator_user_entity_favourite.id is not null and " .
            "documentator_space.client_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $userId, $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function addFavourite($pageId, $userId, $date) {
        $query = "INSERT INTO documentator_user_entity_favourite(entity_id, user_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $pageId, $userId, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function removeAsFavouriteForUsers($pageId) {
        $query = "delete from documentator_user_entity_favourite where entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function removeAsFavouriteForUserId($pageId, $userId) {
        $query = "delete from documentator_user_entity_favourite where entity_id = ? and user_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $pageId, $userId);
            $stmt->execute();
        }
    }

    public static function deleteById($pageId) {
        $query = "delete from documentator_entity where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function updateChildrenAsTopLevelPages($pageId) {
        $query = "update documentator_entity set parent_entity_id = NULL where parent_entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function removeAsHomePage($spaceId, $pageId) {
        $query = "update documentator_space set home_entity_id = NULL where home_entity_id = ? and id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $pageId, $spaceId);
            $stmt->execute();
        }
    }

    public static function addRevision($pageId, $userId, $content, $date) {
        $query = "INSERT INTO documentator_entity_revision(entity_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiss", $pageId, $userId, $content, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getRevisionsByPageId($pageId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "user.first_name, user.last_name, user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join user on user.id = documentator_entity_revision.user_id " .
            "where documentator_entity_revision.entity_id = ? " .
            "order by documentator_entity_revision.id desc";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getRevisionsByAttachmentId($attachmentId) {
        $query = "SELECT documentator_entity_attachment_revision.id, documentator_entity_attachment_revision.date_created, " .
            'user.id as user_id, user.first_name, user.last_name ' .
            'from documentator_entity_attachment_revision ' .
            'left join user on documentator_entity_attachment_revision.user_created_id = user.id ' .
            'where documentator_entity_attachment_revision.documentator_entity_attachment_id = ? ' .
            'order by id desc';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $attachmentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getLastRevisionByPageId($pageId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "user.first_name, user.last_name, user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join user on user.id = documentator_entity_revision.user_id " .
            "where documentator_entity_revision.entity_id = ? " .
            "order by documentator_entity_revision.id desc " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getRevisionsByPageIdAndRevisionId($pageId, $revisionId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "user.first_name, user.last_name, user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join user on user.id = documentator_entity_revision.user_id " .
            "where documentator_entity_revision.entity_id = ? and " .
            "documentator_entity_revision.id = ? " .
            "order by documentator_entity_revision.id desc";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $pageId, $revisionId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getChildren($pageId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "user.id as user_id, user.first_name, user.last_name " .
            "FROM documentator_entity " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "where documentator_entity.parent_entity_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteRevisionById($revisionId) {
        $query = "delete from documentator_entity_revision where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $revisionId);
            $stmt->execute();
        }
    }

    public static function updateContent($pageId, $content) {
        $query = "update documentator_entity set content = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("si", $content, $pageId);
            $stmt->execute();
        }
    }

    public static function findBySpaceIdAndKeyword($clientId, $spaceId, $pageNameKeyword) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "user.id as user_id, user.first_name, user.last_name, " .
            "documentator_space.name as space_name " .
            "FROM documentator_entity " .
            "left join documentator_space on documentator_space.id = documentator_entity.documentator_space_id " .
            "left join user on user.id = documentator_entity.user_created_id " .
            "where ";

        if ($spaceId == -1) {
            $query .= 'documentator_space.client_id = ' . $clientId;
        } else {
            $query .= 'documentator_space.id = ' . $spaceId;
        }

        $query .= " and documentator_entity.name like '%" . $pageNameKeyword . "%' ";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteRevisionsByEntityId($pageId) {
        $query = "delete from documentator_entity_revision where entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function moveToTrash($pageId) {
        $query = "update documentator_entity set in_trash_flag = 1 where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function restoreById($pageId) {
        $query = "update documentator_entity set in_trash_flag = 0 where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public static function getTypes() {
        $query = "SELECT * from documentator_entity_type";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getFilesByEntityId($entityId) {
        $query = "SELECT * from documentator_entity_file where documentator_entity_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $entityId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getFileByName($entityId, $filename) {
        $query = "SELECT * from documentator_entity_file where documentator_entity_id = ? and name = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("is", $entityId, $filename);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function addFile($entityId, $filename, $currentDate) {
        $query = "INSERT INTO documentator_entity_file(documentator_entity_id, name, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iss", $entityId, $filename, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getRevisionsByFileId($fileId) {
        $query = "SELECT documentator_entity_file_revision.id, documentator_entity_file_revision.date_created, " .
                    'user.id as user_id, user.first_name, user.last_name ' .
                    'from documentator_entity_file_revision ' .
                    'left join user on documentator_entity_file_revision.user_created_id = user.id ' .
                    'where documentator_entity_file_id = ? ' .
                    'order by id desc';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fileId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function addFileRevision($fileId, $userId, $currentDate) {
        $query = "INSERT INTO documentator_entity_file_revision(documentator_entity_file_id, user_created_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $fileId, $userId, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getFileById($fileId) {
        $query = "SELECT * from documentator_entity_file where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fileId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function deleteFileRevisions($fileId) {
        $query = "delete from documentator_entity_file_revision where documentator_entity_file_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $fileId);
            $stmt->execute();
        }
    }

    public static function deleteFileById($entityId, $fileId) {
        Entity::deleteFileRevisions($fileId);

        // remove file from database
        $query = "delete from documentator_entity_file where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $fileId);
            $stmt->execute();
        }

        // delete folder from disk
        $filelistsPathBase = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
        Util::deleteDir($filelistsPathBase . $entityId . '/' . $fileId);
    }

    public static function deleteFilesByEntityId($entityId) {
        $files = Entity::getFilesByEntityId($entityId);
        if ($files) {
            while ($file = $files->fetch_array(MYSQLI_ASSOC)) {
                // delete the revisions
                Entity::deleteFileRevisions($file['id']);
            }
        }

        // delete folder from disk
        $filelistsPathBase = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
        Util::deleteDir($filelistsPathBase . $entityId);

        // remove file from database
        $query = "delete from documentator_entity_file where documentator_entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $entityId);
            $stmt->execute();
        }
    }

    public static function addSnapshot($entityId, $entityContent, $userId, $date) {
        $query = "INSERT INTO documentator_entity_snapshot(documentator_entity_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiss", $entityId, $userId, $entityContent, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function deleteAllSnapshotsByEntityIdAndUserId($entityId, $userId, $entityLastSnapshotId = null) {
        $query = "DELETE FROM documentator_entity_snapshot where documentator_entity_id = ? and user_id = ?";
        if (isset($entityLastSnapshotId) && $entityLastSnapshotId != -1)
            $query .= ' and id != ' . $entityLastSnapshotId;

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $entityId, $userId);
            $stmt->execute();
        }
    }

    public static function getOtherActiveSnapshots($entityId, $userId, $currentTime, $resultType = null) {
        $query = "select user_id, TIMESTAMPDIFF(MINUTE, documentator_entity_snapshot.date_created, '" . $currentTime . "') as last_edit_offset, " .
                 "user.first_name, user.last_name " .
                 "FROM documentator_entity_snapshot " .
                 "left join user on user.id = documentator_entity_snapshot.user_id " .
                 "where documentator_entity_id = ? and " .
                 "user_id != ? " .
                 "order by last_edit_offset";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $entityId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows) {
                if ($resultType == 'array') {
                    $resultArray = array();
                    while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                        $resultArray[] = $data;
                    }
                    return $resultArray;
                } else {
                    return $result;
                }
            } else {
                return null;
            }
        }
    }

    public static function getLastSnapshot($entityId, $userId) {
        $query = "SELECT * from documentator_entity_snapshot where documentator_entity_id = ? and user_id = ? order by id desc limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $entityId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getSnapshotById($snapshotId) {
        $query = "SELECT * from documentator_entity_snapshot where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $snapshotId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function deleteSnapshotById($snapShotId) {
        $query = "DELETE FROM documentator_entity_snapshot where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $snapShotId);
            $stmt->execute();
        }
    }

    public static function updateParent($entityId, $parentId) {
        $query = "update documentator_entity set parent_entity_id = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $parentId, $entityId);
            $stmt->execute();
        }
    }

    public static function getAll($filters = null) {
        $query = "SELECT * from documentator_entity";

        if (empty($filters['sort_by'])) {
            $query .= ' order by documentator_entity.id';
        }
        else {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }
}