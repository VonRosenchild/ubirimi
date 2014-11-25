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

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;

?>
<table class="table table-hover table-condensed" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Username</th>
            <th>Email address</th>
            <th>Groups</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($users): ?>
            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $user['id'] ?>">
                    <td width="12px">
                        <input type="checkbox" value="1" id="el_check_<?php echo $user['id'] ?>" />
                    </td>
                    <td width="20%">
                        <?php echo LinkHelper::getUserProfileLink($user['id'], SystemProduct::SYS_PRODUCT_YONGO, $user['first_name'], $user['last_name']) ?>
                    </td>
                    <td><?php echo $user['username'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td width="30%">
                        <?php $groups = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getByUserIdAndProductId($user['id'], SystemProduct::SYS_PRODUCT_YONGO); ?>
                        <?php if ($groups): ?>
                            <ul>
                                <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                    <li><?php echo $group['name'] ?></li>
                                <?php endwhile ?>
                            </ul>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endwhile ?>
        <?php endif ?>
    </tbody>
</table>