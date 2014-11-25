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
            $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows/issue-type-schemes">Workflow Issue Type Schemes</a> > ' . $issueTypeScheme['name'] . ' > Edit';
        } else {
            $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a> > ' . $issueTypeScheme['name'] . ' > Edit';
        }
        Util::renderBreadCrumb($breadCrumb)
    ?>
    <div class="pageContent">
        <form name="add_status" action="/yongo/administration/issue/edit-type-scheme/<?php echo $issueTypeSchemeId ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="150">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($typeExists): ?>
                            <div class="error">A scheme with the same name already exists.</div>
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
                            <?php
                                $found = false;
                                while ($schemeIssueTypes && $schemeType = $schemeIssueTypes->fetch_array(MYSQLI_ASSOC)) {
                                    if ($schemeType['issue_type_id'] == $issueType['id']) {
                                        $found = true;
                                        break;
                                    }
                                }
                                if ($schemeIssueTypes) {
                                    $schemeIssueTypes->data_seek(0);
                                }
                            ?>
                            <input <?php if ($found) echo 'checked="checked"' ?> type="checkbox" value="1" name="issue_type_<?php echo $issueType['id'] ?>"/>
                            <span><?php echo $issueType['name'] ?></span>
                        </td>
                    </tr>
                <?php endwhile ?>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <button type="submit" name="edit_type_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Scheme</button>
                        <?php if ('workflow' == $type): ?>
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