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

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class Newsletter
{
    public function addSubscription($emailAddress, $currentDate) {
        $query = "INSERT INTO newsletter(email_address, date_created) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $emailAddress, $currentDate);
        $stmt->execute();
    }

    public function checkEmailAddressDuplication($emailAddress) {
        $query = 'select id from newsletter where LOWER(email_address) = LOWER(?) limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $emailAddress);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array();
    }
}
