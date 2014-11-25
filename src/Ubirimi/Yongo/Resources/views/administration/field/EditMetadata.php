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
    <?php Util::renderBreadCrumb('<a href="/yongo/administration/custom-fields" class="linkNoUnderline">Custom Fields</a> > Edit Custom Field') ?>
    <div class="pageContent">
        <form name="edit_custom_field" action="/yongo/administration/custom-field/edit/<?php echo $Id ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $customField['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($duplicate_name): ?>
                            <div class="error">A custom field with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $customField['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_custom_field" class="btn ubirimi-btn"><i class="icon-edit"></i> Update</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/custom-fields">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>