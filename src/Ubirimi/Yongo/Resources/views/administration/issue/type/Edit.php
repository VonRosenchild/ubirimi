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
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue-types">Issue Types</a> > Edit Type') ?>
    <div class="pageContent">
        <form name="edit_type" action="/yongo/administration/issue/edit-type/<?php echo $Id ?>" method="post">
            
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $issueType['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($typeExists): ?>
                            <div class="error">A type with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php echo $issueType['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <button type="submit" name="edit_type" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Type</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/issue-types">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>