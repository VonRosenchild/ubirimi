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
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Issue Type Schemes') ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/issue-types">Issue Types</a></li>
                <li class="active"><a href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a></li>
                <li><a href="/yongo/administration/issue-sub-tasks">Sub-Tasks</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/add-type-scheme/project" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Type Scheme</a></td>
                    <td><a id="btnEditIssueTypeScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnCopyIssueTypeScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteIssueTypeScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
            <div class="infoBox">An issue type scheme determines which issue types will be available to a set of projects. It also allows to specify the order in which the issue types are presented in the user interface.</div>
            <?php if ($issueTypeSchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Options</th>
                            <th>Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scheme = $issueTypeSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $scheme['id'] ?>">
                                <td>
                                    <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>" />
                                    <?php echo $scheme['name'] ?>
                                    <div class="smallDescription" style="padding-left: 25px;"><?php echo $scheme['description'] ?></div>
                                </td>
                                <td>
                                    <?php
                                        $dataIssueTypeScheme = UbirimiContainer::get()['repository']->get(IssueTypeScheme::class)->getDataById($scheme['id']);
                                        if ($dataIssueTypeScheme) {
                                            while ($data = $dataIssueTypeScheme->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<div>';
                                                    echo '<img height="16px" src="/yongo/img/issue_type/' . $data['issue_type_icon_name'] . '" />';
                                                    echo '<span> ' . $data['name'] . '</span>';
                                                echo '</div>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByIssueTypeScheme($scheme['id']);
                                        if ($projects) {
                                            while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                                echo '&#8226; <a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a>';
                                                echo '<br />';
                                            }

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
                <div class="messageGreen">There are no issue type schemes defined</div>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteIssueTypeScheme"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>