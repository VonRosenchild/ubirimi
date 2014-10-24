<?php

namespace Ubirimi\Documentador\Repository\Entity;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class EntityAttachment {

    public function getByEntityIdAndName($entityId, $attachmentName) {
        $query = "SELECT * from documentator_entity_attachment where documentator_entity_id = ? and name = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("is", $entityId, $attachmentName);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function getById($attachmentId) {
        $query = "SELECT * from documentator_entity_attachment where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $attachmentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function deleteRevisionsByAttachmentId($attachmentId) {
        $query = "delete from documentator_entity_attachment_revision where documentator_entity_attachment_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $attachmentId);
            $stmt->execute();
        }
    }

    public function deleteByEntityId($entityId, $spaceId) {
        $attachments = EntityAttachment::getByEntityId($entityId);
        if ($attachments) {
            while ($attachment = $attachments->fetch_array(MYSQLI_ASSOC)) {
                // delete the revisions
                EntityAttachment::deleteRevisionsByAttachmentId($attachment['id']);
            }
        }

        // delete folder from disk
        $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
        Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId);

        // remove attachment revisions
        $query = "delete from documentator_entity_attachment where documentator_entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $entityId);
            $stmt->execute();
        }
    }

    public function add($entityId, $fileName, $currentDate) {
        $query = "INSERT INTO documentator_entity_attachment(documentator_entity_id, name, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iss", $entityId, $fileName, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getByEntityId($entityId) {
        $query = "SELECT * from documentator_entity_attachment where documentator_entity_id = ?";

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

    public function addRevision($attachmentId, $userId, $currentDate) {
        $query = "INSERT INTO documentator_entity_attachment_revision(documentator_entity_attachment_id, user_created_id, date_created) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iis", $attachmentId, $userId, $currentDate);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function deleteById($spaceId, $entityId, $attachmentId) {
        EntityAttachment::deleteRevisionsByAttachmentId($attachmentId);

        // delete folder from disk
        $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
        Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId . '/' . $attachmentId);

        // remove attachment revisions
        $query = "delete from documentator_entity_attachment where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $attachmentId);
            $stmt->execute();
        }
    }
}