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

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;

class BugzillaConnectorService
{
    public function getConnection() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $connection = new \mysqli(
            UbirimiContainer::get()['bugzilla.database.host'],
            UbirimiContainer::get()['bugzilla.database.user'],
            UbirimiContainer::get()['bugzilla.database.password'],
            UbirimiContainer::get()['bugzilla.database.name']);

        if ($connection->connect_errno) {
            throw new \Exception(sprintf('Failed to connect to database (%d). Reason: %s', $connection->connect_errno, $connection->connect_error));
        }

        $query = "SET NAMES 'utf8'";

        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
        }

        return $connection;
    }
}