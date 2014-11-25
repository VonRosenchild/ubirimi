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

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php
            $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a> > ' . $issueSecurityScheme['name'] . ' > Levels';
            Util::renderBreadCrumb($breadCrumb)
        ?>
    <?php endif ?>
    <div class="pageContent">

        <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/issue-security-scheme/level/add/<?php echo $issueSecuritySchemeId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Level</a></td>
                <td><a id="btnEditIssueSecuritySchemeLevel" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteIssueSecuritySchemeLevel" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <div class="infoBox">
            On this page you can create and delete the issue security levels for the "<?php echo $issueSecurityScheme['name'] ?>" issue security scheme.<br/>
            Each security level can have users/groups assigned to them.<br/>
            An issue can then be assigned a Security Level. This ensures only users who are assigned to this security level may view the issue.
        </div>
        <?php if ($issueSecuritySchemeLevels): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Users / Groups / Project Roles</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>

                    <?php while ($level = $issueSecuritySchemeLevels->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $level['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $level['id'] ?>"/>
                            </td>
                            <td>
                                <div>
                                    <?php echo $level['name'] ?>
                                    <?php if ($level['default_flag']) echo ' (default)' ?>
                                </div>
                            </td>
                            <td>
                                <div><?php echo $level['description'] ?></div>
                            </td>
                            <td>
                                <?php
                                    $securitySchemeData = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getDataByLevelId($level['id']);
                                    if ($securitySchemeData) {
                                        echo '<ul>';
                                        while ($data = $securitySchemeData->fetch_array(MYSQLI_ASSOC)) {
                                            $link_delete = '<a id="delete_security_data_' . $data['id'] . '" href="#">remove</a>';
                                            if ($data['current_assignee']) {
                                                echo '<li>Current Assignee (' . $link_delete . ')</li>';
                                            } else if ($data['reporter']) {
                                                echo '<li>Reporter (' . $link_delete . ')</li>';
                                            } else if ($data['project_lead']) {
                                                echo '<li>Project Lead (' . $link_delete . ')</li>';
                                            } else if ($data['first_name']) {
                                                echo '<li>Single User (' . $data['first_name'] . ' ' . $data['last_name'] . ') (' . $link_delete . ')</li>';
                                            } else if ($data['group_name']) {
                                                echo '<li> Group (' . $data['group_name'] . ') (' . $link_delete . ')</li>';
                                            } else if ($data['role_name']) {
                                                echo '<li>Project Role (' . $data['role_name'] . ') (' . $link_delete . ')</li>';
                                            }
                                        }
                                        echo '</ul>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="/yongo/administration/issue-security-scheme/level-data/add/<?php echo $level['id'] ?>">Add</a>
                                <?php if (!$level['default_flag']): ?>
                                    <a href="/yongo/administration/do-security-level-default/<?php echo $level['id'] ?>">Default</a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no issue security levels defined for this scheme.</div>
        <?php endif ?>
    </div>
        <div class="ubirimiModalDialog" id="modalDeleteIssueSecuritySchemeLevel"></div>
        <div class="ubirimiModalDialog" id="modalDeleteIssueSecuritySchemeLevelData"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php
        endif ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>