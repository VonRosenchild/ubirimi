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

namespace Ubirimi\Repository\User;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;

class UbirimiGroup
{
    public function getByName($clientId, $name) {
        $query = 'select * FROM `general_group` where client_id = ? and name = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function addDefaultYongoGroups($clientId, $date) {
        $query = "INSERT INTO `general_group`(client_id, sys_product_id, name, description, date_created) VALUES (?, ?, ?, ?, ?), (?, ?, ?, ?, ?), (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $group_name_1 = 'Administrators';
        $group_name_2 = 'Developers';
        $group_name_3 = 'Users';
        $group_descr_1 = 'The users in this group will have all the privileges';
        $group_descr_2 = 'The users in this group will have some privileges';
        $group_descr_3 = 'The users in this group will have basic privileges';

        $productId = SystemProduct::SYS_PRODUCT_YONGO;
        $stmt->bind_param("iisssiisssiisss", $clientId, $productId, $group_name_1, $group_descr_1, $date, $clientId, $productId, $group_name_2, $group_descr_2, $date, $clientId, $productId, $group_name_3, $group_descr_3, $date);

        $stmt->execute();
    }

    public function getByNameAndProductId($clientId, $productId, $name, $groupId = null) {
        $query = 'select id, name FROM `general_group` where client_id = ? and sys_product_id = ? and lower(name) = ?';
        if ($groupId)
            $query .= ' and id != ' . $groupId;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $clientId, $productId, $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getByUserIdAndProductId($userId, $productId) {
        $query = 'SELECT general_group.name, general_group.id ' .
            'from general_group_data ' .
            'left join `general_group` on general_group.id = general_group_data.group_id ' .
            'where general_group_data.user_id = ? and ' .
            '`general_group`.sys_product_id = ? ' .
            'order by `general_group`.name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientIdAndProductId($clientId, $productId) {
        $query = 'SELECT * FROM `general_group` where client_id = ? and sys_product_id = ? order by `general_group`.name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientId($clientId) {
        $query = 'SELECT * FROM `general_group` where client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addData($groupId, $userArray, $currentDate) {
        $query = 'insert into general_group_data(group_id, user_id, date_created) values ';

        for ($i = 0; $i < count($userArray); $i++)
            $query .= '(' . $groupId . ' ,' . $userArray[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function add($clientId, $productId, $name, $description, $currentDate) {
        $query = "INSERT INTO `general_group`(client_id, sys_product_id, name, description, date_created) VALUES (?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisss", $clientId, $productId, $name, $description, $currentDate);
        $stmt->execute();
    }

    public function getMetadataById($Id) {
        $query = 'SELECT ' .
            'id, name, description, client_id ' .
            'FROM `general_group` ' .
            'where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateById($Id, $name, $description, $date) {
        $query = "update `general_group` set name = ?, description = ?, date_updated = ? where id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getDataByGroupId($groupId) {
        $query = 'select general_group_data.id, general_group_data.user_id, general_user.first_name, general_user.last_name, ' .
                 'general_user.issues_display_columns ' .
            'from general_group_data ' .
            'left join general_user on general_user.id = general_group_data.user_id ' .
            'where general_group_data.group_id = ? ' .
            'order by general_user.first_name, general_user.last_name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteDataByGroupId($groupId) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'DELETE from general_group_data where group_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = "SET FOREIGN_KEY_CHECKS = 1;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteByIdForDocumentador($groupId) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from general_group_data where group_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = 'delete FROM `general_group` where id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteByIdForYongo($groupId) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from general_group_data where group_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = 'delete FROM `general_group` where id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = 'delete from notification_scheme_data where group_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = 'delete from permission_scheme_data where group_id = ?';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();

        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);
    }
}
