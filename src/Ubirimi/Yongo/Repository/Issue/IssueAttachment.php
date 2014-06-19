<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class IssueAttachment {

    public static function getAll() {
        $query = 'select id, issue_id, name, size from issue_attachment';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($Id) {
        $query = 'select id, issue_id, name, size from issue_attachment where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result->fetch_array();
            else
                return null;
        }
    }

    public static function getByIssueId($issueId, $fetchAll = false) {
        $query = 'SELECT issue_attachment.id, issue_id, name, size, issue_attachment.date_created,' .
            'user.id as user_id, user.first_name, user.last_name ' .
            'FROM issue_attachment ' .
            'LEFT JOIN user on issue_attachment.user_id = user.id ' .
            'WHERE issue_id = ? ' .
            'ORDER BY issue_attachment.date_created ASC';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($fetchAll) {
                return $result->fetch_all(MYSQL_ASSOC);
            } else {
                if ($result->num_rows)
                    return $result;
                else
                    return null;
            }
        }
    }

    public static function deleteById($attachmentId) {
        $query = "DELETE FROM issue_attachment WHERE id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $attachmentId);
            $stmt->execute();
        }
    }

    public static function deleteByIssueId($issueId) {
        $attachments = IssueAttachment::getByIssueId($issueId);

        if ($attachments) {
            while ($attachment = $attachments->fetch_array(MYSQLI_ASSOC)) {
                $attachmentId = $attachment['id'];
                unlink(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId . '/' . $attachment['name']);
                if (Util::isImage(Util::getExtension($attachment['name']))) {
                    unlink(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId . '/thumbs/' . $attachment['name']);
                    Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId . '/thumbs');
                }

                Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId);
            }

            // also delete the folder
            if (file_exists(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId)) {
                Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId);
            }
        }
    }

    public static function updateByIdAndIssueId($attachmentId, $issueId) {
        $query = 'UPDATE issue_attachment SET issue_id = ? where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueId, $attachmentId);
            $stmt->execute();
        }
    }

    public static function add($issueId, $name, $userId, $date) {
        $query = "INSERT INTO issue_attachment(issue_id, user_id, name, date_created) VALUES (?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iiss", $issueId, $userId, $name, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function updateSizeById($attachmentId, $size) {
        $query = "UPDATE issue_attachment SET size = ? WHERE id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $size, $attachmentId);
            $stmt->execute();
        }
    }
}