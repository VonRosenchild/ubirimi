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

use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Users') ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnAssignUserToGroup" href="#" class="btn ubirimi-btn disabled">Groups</a></td>
                <td><a id="btnUserAssignInProjectRole" href="#" class="btn ubirimi-btn disabled">Project Roles</a></td>
            </tr>
        </table>

        <table cellspacing="0" border="0" cellpadding="0" width="100%">
            <tr>
                <td width="260px">Username contains</td>
                <td width="260px">Full name contains</td>
                <td>In Group</td>
            </tr>
            <tr>
                <td><input class="inputText" type="text" id="username_filter" /></td>
                <td><input class="inputText" type="text" id="fullname_filter" /></td>
                <td>
                    <select id="group_filter" class="select2Input">
                        <option value="-1">All Groups</option>
                        <?php if ($allGroups): ?>
                            <?php while ($group = $allGroups->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($group['id'] == $filterGroupId) echo 'selected="selected"' ?> value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                            <?php endwhile ?>
                        <?php endif ?>
                    </select>
                </td>
            </tr>
        </table>

        <div id="contentListUsers">
            <?php require_once __DIR__ . '/../../../views/administration/user/_list_user.php' ?>
        </div>
        <div class="ubirimiModalDialog" id="modalDeleteUser"></div>
        <div class="ubirimiModalDialog" id="modalAssignUserToGroup"></div>
        <input type="hidden" value="<?php echo SystemProduct::SYS_PRODUCT_YONGO ?>" id="product_id" />
        <?php else: ?>
        <div class="infoBox">Unauthorized access. Please contact the system administrator.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>