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

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a href="/yongo/administration/issue/priorities" class="linkNoUnderline">Issue Priorities</a> > Create Priority') ?>
    <div class="pageContent">

        <form name="add_priority" action="/yongo/administration/issue/priority/add" method="post">
            
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" class="inputText"/>
                        <?php if ($emptyPriorityName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($priorityExists): ?>
                            <div class="error">A priority with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Color</td>
                    <td>
                        <input class="inputText color {hash:true}" style="width: 100px" name="color" value="<?php if (isset($color)) echo $color ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="new_priority" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Priority</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue/priorities">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>