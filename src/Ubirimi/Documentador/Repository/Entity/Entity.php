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

namespace Ubirimi\Documentador\Repository\Entity;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
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

    function __construct($entityTypeId = null, $spaceId = null, $userCreatedId = null, $parentPageId = null, $name = null, $content = null) {
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

    public function getAllBySpaceId($spaceId, $inTrashFlag = null) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, " .
                 "documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
                 "documentator_entity.parent_entity_id, " .
                 "general_user.id as user_id, general_user.first_name, general_user.last_name " .
            "FROM documentator_entity " .
            "left join general_user on general_user.id = documentator_entity.user_created_id " .
            "where documentator_entity.documentator_space_id = ? ";

        if (isset($inTrashFlag)) {
            $query .= " and documentator_entity.in_trash_flag = " . $inTrashFlag;
        }

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

    public function getById($pageId, $userId = null, $inTrashFlag = null) {
        $query = "SELECT documentator_entity.name, documentator_entity.documentator_entity_type_id, documentator_entity.id, " .
                 "documentator_entity.date_created, documentator_entity.content, in_trash_flag, documentator_entity.parent_entity_id, " .
                 "documentator_entity.documentator_space_id as space_id, " .
                 "general_user.id as user_id, general_user.first_name, general_user.last_name, " .
                 "documentator_space.name as space_name ";

        if ($userId)
            $query .= ", documentator_user_entity_favourite.id as fav_id ";

        $query .= "FROM documentator_entity " .
            "left join general_user on general_user.id = documentator_entity.user_created_id " .
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

    public function updateById($pageId, $name, $content, $date) {
        $query = "update documentator_entity set name = ?, content = ?, date_updated = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("sssi", $name, $content, $date, $pageId);
            $stmt->execute();
        }
    }

    public function getFavouritePagesByClientIdAndUserId($clientId, $userId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "general_user.id as user_id, general_user.first_name, general_user.last_name " .
            "FROM documentator_entity " .
            "left join general_user on general_user.id = documentator_entity.user_created_id " .
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

    public function addFavourite($pageId, $userId, $date) {
        $query = "INSERT INTO documentator_user_entity_favourite(entity_id, user_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $pageId, $userId, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function removeAsFavouriteForUsers($pageId) {
        $query = "delete from documentator_user_entity_favourite where entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function removeAsFavouriteForUserId($pageId, $userId) {
        $query = "delete from documentator_user_entity_favourite where entity_id = ? and user_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $pageId, $userId);
            $stmt->execute();
        }
    }

    public function deleteById($pageId) {
        $query = "delete from documentator_entity where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function updateChildrenAsTopLevelPages($pageId) {
        $query = "update documentator_entity set parent_entity_id = NULL where parent_entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function removeAsHomePage($spaceId, $pageId) {
        $query = "update documentator_space set home_entity_id = NULL where home_entity_id = ? and id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $pageId, $spaceId);
            $stmt->execute();
        }
    }

    public function addRevision($pageId, $userId, $content, $date) {
        $query = "INSERT INTO documentator_entity_revision(entity_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiss", $pageId, $userId, $content, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getRevisionsByPageId($pageId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "general_user.first_name, general_user.last_name, general_user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join general_user on general_user.id = documentator_entity_revision.user_id " .
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

    public function getRevisionsByAttachmentId($attachmentId) {
        $query = "SELECT documentator_entity_attachment_revision.id, documentator_entity_attachment_revision.date_created, " .
            'general_user.id as user_id, general_user.first_name, general_user.last_name ' .
            'from documentator_entity_attachment_revision ' .
            'left join general_user on documentator_entity_attachment_revision.user_created_id = general_user.id ' .
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

    public function getLastRevisionByPageId($pageId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "general_user.first_name, general_user.last_name, general_user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join general_user on general_user.id = documentator_entity_revision.user_id " .
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

    public function getRevisionsByPageIdAndRevisionId($pageId, $revisionId) {
        $query = "SELECT documentator_entity_revision.id, documentator_entity_revision.content, documentator_entity_revision.user_id, documentator_entity_revision.date_created, " .
            "general_user.first_name, general_user.last_name, general_user.id as user_id " .
            "FROM documentator_entity_revision " .
            "left join general_user on general_user.id = documentator_entity_revision.user_id " .
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

    public function getChildren($pageId) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, " .
            "documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "general_user.id as user_id, general_user.first_name, general_user.last_name " .
            "FROM documentator_entity " .
            "left join general_user on general_user.id = documentator_entity.user_created_id " .
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

    public function deleteRevisionById($revisionId) {
        $query = "delete from documentator_entity_revision where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $revisionId);
            $stmt->execute();
        }
    }

    public function updateContent($pageId, $content) {
        $query = "update documentator_entity set content = ? where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("si", $content, $pageId);
            $stmt->execute();
        }
    }

    public function findBySpaceIdAndKeyword($clientId, $spaceId, $pageNameKeyword) {
        $query = "SELECT documentator_entity.documentator_space_id as space_id, documentator_entity.name, documentator_entity.id, documentator_entity.date_created, documentator_entity.content, " .
            "general_user.id as user_id, general_user.first_name, general_user.last_name, " .
            "documentator_space.name as space_name " .
            "FROM documentator_entity " .
            "left join documentator_space on documentator_space.id = documentator_entity.documentator_space_id " .
            "left join general_user on general_user.id = documentator_entity.user_created_id " .
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

    public function deleteRevisionsByEntityId($pageId) {
        $query = "delete from documentator_entity_revision where entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function moveToTrash($pageId) {
        $query = "update documentator_entity set in_trash_flag = 1 where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function restoreById($pageId) {
        $query = "update documentator_entity set in_trash_flag = 0 where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }

    public function getTypes() {
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

    public function getFilesByEntityId($entityId) {
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

    public function getFileByName($entityId, $filename) {
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

    public function addFile($entityId, $filename, $currentDate) {
        $query = "INSERT INTO documentator_entity_file(documentator_entity_id, name, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iss", $entityId, $filename, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getRevisionsByFileId($fileId) {
        $query = "SELECT documentator_entity_file_revision.id, documentator_entity_file_revision.date_created, " .
                    'general_user.id as user_id, general_user.first_name, general_user.last_name ' .
                    'from documentator_entity_file_revision ' .
                    'left join general_user on documentator_entity_file_revision.user_created_id = general_user.id ' .
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

    public function addFileRevision($fileId, $userId, $currentDate) {
        $query = "INSERT INTO documentator_entity_file_revision(documentator_entity_file_id, user_created_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $fileId, $userId, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getFileById($fileId) {
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

    public function deleteFileRevisions($fileId) {
        $query = "delete from documentator_entity_file_revision where documentator_entity_file_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $fileId);
            $stmt->execute();
        }
    }

    public function deleteFileById($entityId, $fileId) {
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

    public function deleteFilesByEntityId($entityId) {
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

    public function addSnapshot($entityId, $entityContent, $userId, $date) {
        $query = "INSERT INTO documentator_entity_snapshot(documentator_entity_id, user_id, content, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiss", $entityId, $userId, $entityContent, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function deleteAllSnapshotsByEntityIdAndUserId($entityId, $userId, $entityLastSnapshotId = null) {
        $query = "DELETE FROM documentator_entity_snapshot where documentator_entity_id = ? and user_id = ?";
        if (isset($entityLastSnapshotId) && $entityLastSnapshotId != -1)
            $query .= ' and id != ' . $entityLastSnapshotId;

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $entityId, $userId);
            $stmt->execute();
        }
    }

    public function getOtherActiveSnapshots($entityId, $userId, $currentTime, $resultType = null) {
        $query = "select user_id, TIMESTAMPDIFF(MINUTE, documentator_entity_snapshot.date_created, '" . $currentTime . "') as last_edit_offset, " .
                 "general_user.first_name, general_user.last_name " .
                 "FROM documentator_entity_snapshot " .
                 "left join general_user on general_user.id = documentator_entity_snapshot.user_id " .
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

    public function getLastSnapshot($entityId, $userId) {
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

    public function getSnapshotById($snapshotId) {
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

    public function deleteSnapshotById($snapShotId) {
        $query = "DELETE FROM documentator_entity_snapshot where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $snapShotId);
            $stmt->execute();
        }
    }

    public function updateParent($entityId, $parentId) {
        $query = "update documentator_entity set parent_entity_id = ? where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $parentId, $entityId);
            $stmt->execute();
        }
    }

    public function getAll($filters = null) {
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

    public function renderTreeNavigation($treeStructure, $parentPosition, $index, $visible) {
        $html = '';
        foreach ($treeStructure as $parent => $childData) {
            if ($parent == $parentPosition) {
                foreach ($childData as $indexPosition => $data) {
                    if (!$visible) {
                        $style = 'style="display: none; overflow: visible;"';
                    } else {
                        $style = 'style="display: block; overflow: visible;"';
                    }
                    $html .= '<div ' . $style . ' id="tree_' . $parentPosition . '_' . $data['id'] . '">';

                    $smallIndent = '';
                    $bigIndent = '';
                    for ($i = 0; $i < $index * 2; $i++) {
                        $smallIndent .= '&nbsp;';
                    }
                    for ($i = 0; $i < $index * 3; $i++) {
                        $bigIndent .= '&nbsp;';
                    }
                    $html .= '';
                    if (array_key_exists($data['id'], $treeStructure)) {
                        $html .= $smallIndent . '<a style="margin-top: -8px;" href="#" id="tree_show_content_' . $data['id'] . '_x">';
                        $html .= '<img style="vertical-align: middle;" src="/documentador/img/arrow_down.png" /></a> ' . LinkHelper::getDocumentadorPageLink($data['id'], $data['title']);
                    } else {
                        $html .= $bigIndent . '&bullet; ' . LinkHelper::getDocumentadorPageLink($data['id'], $data['title']);
                    }

                    $index++;
                    $html .= UbirimiContainer::get()['repository']->get(Entity::class)->renderTreeNavigation($treeStructure, $data['id'], $index, $data['expanded']);
                    $html .= '</div>';
                    $index--;
                }
            }
        }

        return $html;
    }
}