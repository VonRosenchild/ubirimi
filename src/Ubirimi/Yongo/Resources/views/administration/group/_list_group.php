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
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

?>
<table class="table table-hover table-condensed" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Description</th>
            <th>Users</th>
            <th>Permission Schemes</th>
        </tr>
    </thead>
    <?php if ($groups): ?>
        <tbody>
        <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
            <tr id="table_row_<?php echo $group['id'] ?>">
                <td width="22px"><input type="checkbox" value="1" id="el_check_<?php echo $group['id'] ?>" /></td>
                <td width="40%"><?php echo $group['name'] ?></td>
                <td width="30%"><?php echo $group['description'] ?></td>
                <td width="10%">
                    <?php
                        $usersInGroup = UbirimiContainer::get()['repository']->get(UbirimiGroup::class)->getDataByGroupId($group['id']);
                        if ($usersInGroup) {
                            echo '<a href="/yongo/administration/users/' . $group['id'] . '">' . $usersInGroup->num_rows . '</a>';
                        } else {
                            echo '0';
                        }
                    ?>
                </td>
                <td width="20%">
                    <?php
                        $permissionSchemes = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getByClientIdAndGroupBy($clientId, $group['id']);
                        if ($permissionSchemes) {
                            echo '<ul>';
                            while ($permission = $permissionSchemes->fetch_array(MYSQLI_ASSOC)) {
                                echo '<li><a href="/yongo/administration/permission-scheme/edit/' . $permission['id'] . '">' . $permission['name'] . '<a/></li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </td>
            </tr>
        <?php endwhile ?>
        </tbody>
    <?php endif ?>
</table>
