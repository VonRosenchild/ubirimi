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
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/global-permissions">Globals Permissions</a> > Create Permission') ?>
    <div class="pageContent">

        <form id="form_add_global_permission" name="add_global_permission" action="/yongo/administration/global-permission/add" method="post">
            <table width="100%">
                <tr>
                    <td width="100">Permission</td>
                    <td>
                        <select class="select2Input" name="permission">
                            <?php while ($permission = $globalPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $permission['id'] ?>"><?php echo $permission['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Group</td>
                    <td>
                        <select class="select2Input" name="group">
                            <?php while ($group = $allGroups->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" class="btn ubirimi-btn" name="confirm_new_permission">Add Permission</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/global-permissions">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>