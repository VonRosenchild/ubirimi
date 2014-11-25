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

use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Groups') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/group/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Group</a></td>
                    <td><a id="btnEditUserGroup" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteUserGroup" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <td><a id="btnAssignUserInGroup" href="#" class="btn ubirimi-btn disabled">Assign users</a></td>
                </tr>
            </table>
            <table cellspacing="0" border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="260px">Name contains</td>
                </tr>
                <tr>
                    <td><input class="inputText" type="text" id="group_name_filter" /></td>
                </tr>
            </table>
            <div id="contentListGroups">
                <?php require_once __DIR__ . '/_list_group.php'; ?>
            </div>
            <div id="assignUsersInGroup"></div>
            <div id="deleteUserGroup"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>