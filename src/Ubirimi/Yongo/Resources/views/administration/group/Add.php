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
    <?php
        $breadCrumb = '<a href="/yongo/administration/groups" class="linkNoUnderline">Groups</a> > Create Group';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <form name="add_user_group" action="/yongo/administration/group/add" method="post">
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name" />
                        <?php if ($emptyName): ?>
                        <br />
                        <div class="error">The name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                        <br />
                        <div class="error">The name is not available.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td><textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="new_group" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Group</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/groups">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>