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
            Util::renderBreadCrumb('<a href="/yongo/administration/workflows/issue-type-schemes" class="linkNoUnderline">Workflow Issue Type Schemes</a> > Create Workflow Issue Type Scheme');
        } else {
            Util::renderBreadCrumb('<a href="/yongo/administration/issue-type-schemes" class="linkNoUnderline">Issue Type Schemes</a> > Create Issue Type Scheme');
        }
    ?>

    <div class="pageContent">
        <form name="add_status" action="/yongo/administration/issue/add-type-scheme/<?php echo $type ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="150">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyIssueTypeName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($issueTypeExists): ?>
                            <div class="error">A type with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">This scheme has the following issue types assigned</td>
                </tr>
                <?php while ($issueType = $allIssueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" value="1" id="issue_type_<?php echo $issueType['id'] ?>" name="issue_type_<?php echo $issueType['id'] ?>" />
                            <span>
                                <label for="issue_type_<?php echo $issueType['id'] ?>"><?php echo $issueType['name'] ?></label>
                            </span>
                        </td>
                    </tr>
                <?php endwhile ?>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="new_type_scheme" class="btn ubirimi-btn"><?php echo $buttonLabel ?></button>
                        <?php if ($type == 'workflow'): ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflows/issue-type-schemes">Cancel</a>
                        <?php else: ?>
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