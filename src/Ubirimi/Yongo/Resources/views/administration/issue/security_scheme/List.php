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
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Issue Security Schemes') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/issue-security-scheme/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Security Scheme</a></td>
                    <td><a id="btnIssueSecurityLevels" href="#" class="btn ubirimi-btn disabled">Security Levels</a></td>
                    <td><a id="btnEditIssueSecurityScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteIssueSecurityScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>

            <div class="infoBox">
                Issue Security Schemes allow you to control who can and cannot view issues. They consist of a number of security levels which can have users/groups assigned to them.<br/>
                When creating/editing an issue you can specify a level of security for the issue. This ensures only users who are assigned to this security level may view the issue.<br/>
                Please note that you cannot delete issue security schemes which have associated projects.
            </div>
            <?php if ($issueSecuritySchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $scheme['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>"/>
                                </td>
                                <td>
                                    <a href="/yongo/administration/issue-security-scheme-levels/<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></a>

                                    <div class="description"><?php echo $scheme['description'] ?></div>
                                </td>
                                <td>
                                    <?php
                                        $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByIssueSecurityScheme($scheme['id']);
                                        if ($projects) {
                                            echo '<ul>';
                                            while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                                            }
                                            echo '</ul>';
                                            echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="0">';
                                        } else {
                                            echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="1">';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no issue security schemes defined.</div>
            <?php endif ?>
            <div class="ubirimiModalDialog" id="modalDeleteIssueSecurityScheme"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>