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

namespace Ubirimi\HelpDesk\Repository\Organization;

use Ubirimi\Container\UbirimiContainer;

class Organization
{
    public function getByClientId($clientId) {
        $query = 'SELECT * from help_organization where client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByName($clientId, $name, $organizationId = null) {
        $query = 'select id, name, description ' .
            'from help_organization ' .
            'where client_id = ? ' .
            'and LOWER(name) = ? ';

        if ($organizationId)
            $query .= 'and id != ?';

        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($organizationId)
            $stmt->bind_param("isi", $clientId, $name, $organizationId);
        else
            $stmt->bind_param("is", $clientId, $name);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function create($clientId, $name, $date) {
        $query = "INSERT INTO help_organization(client_id, name, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iss", $clientId, $name, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getById($organizationId) {
        $query = 'SELECT * from help_organization where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $organizationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateById($organizationId, $name, $description, $date) {
        $query = 'update help_organization set name = ?, description = ?, date_updated = ? where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $organizationId);
        $stmt->execute();
    }

    public function deleteById($id) {
        $query = 'delete from help_organization WHERE id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $query = 'delete from help_organization_user WHERE help_organization_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
