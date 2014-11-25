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
    <?php
        if ('workflow' == $type) {
            Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/workflows/issue-type-schemes">Workflow Issue Type Schemes</a> > ' . $issueTypeScheme['name'] . ' > Copy');
        } else {
            Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a> > ' . $issueTypeScheme['name'] . ' > Copy');
        }
    ?>
    <div class="pageContent">
        <form name="form_copy_issue_type_scheme" action="/yongo/administration/issue-type-scheme/copy/<?php echo $issueTypeSchemeId ?>?type=<?php echo $type ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $issueTypeScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <?php if ('workflow' == $type): ?>
                                <div class="error">The workflow issue type scheme name can not be empty.</div>
                            <?php else: ?>
                                <div class="error">The issue type scheme name can not be empty.</div>
                            <?php endif ?>
                        <?php elseif ($duplicateName): ?>
                            <?php if ('workflow' == $type): ?>
                                <div class="error">A workflow issue type scheme with the same name already exists.</div>
                            <?php else: ?>
                                <div class="error">An issue type scheme with the same name already exists.</div>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $issueTypeScheme['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php if ('workflow' == $type): ?>
                            <button type="submit" name="copy_issue_type_scheme" class="btn ubirimi-btn">Copy Workflow Issue Type Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflows/issue-type-schemes">Cancel</a>
                        <?php else: ?>
                            <button type="submit" name="copy_issue_type_scheme" class="btn ubirimi-btn">Copy Issue Type Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-type-schemes">Cancel</a>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>