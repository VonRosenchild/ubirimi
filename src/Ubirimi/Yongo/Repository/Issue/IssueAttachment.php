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

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

class IssueAttachment
{
    public function getAll() {
        $query = 'select id, issue_id, name, size from issue_attachment';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($Id) {
        $query = 'select id, issue_id, name, size from issue_attachment where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array();
        else
            return null;
    }

    public function getByIssueId($issueId, $fetchAll = false) {
        $query = 'SELECT issue_attachment.id, issue_id, name, size, issue_attachment.date_created,' .
            'general_user.id as user_id, general_user.first_name, general_user.last_name ' .
            'FROM issue_attachment ' .
            'LEFT join general_user on issue_attachment.user_id = general_user.id ' .
            'WHERE issue_id = ? ' .
            'ORDER BY issue_attachment.date_created ASC';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($fetchAll) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public function deleteById($attachmentId) {
        $query = "DELETE FROM issue_attachment WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $attachmentId);
        $stmt->execute();
    }

    public function deleteByIssueId($issueId) {
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

    public function updateByIdAndIssueId($attachmentId, $issueId) {
        $query = 'UPDATE issue_attachment SET issue_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $attachmentId);
        $stmt->execute();
    }

    public function add($issueId, $name, $userId, $date) {
        $query = "INSERT INTO issue_attachment(issue_id, user_id, name, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiss", $issueId, $userId, $name, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function updateSizeById($attachmentId, $size) {
        $query = "UPDATE issue_attachment SET size = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $size, $attachmentId);
        $stmt->execute();
    }
}
