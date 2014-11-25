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
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Screens') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>


            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active"><a href="/yongo/administration/screens">Screens</a></li>
                <li><a href="/yongo/administration/screens/schemes">Screen Schemes</a></li>
                <li><a href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/screen/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Screen</a></td>
                    <?php if ($screens): ?>
                        <td><a id="btnEditScreenMetaData" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnEditScreen" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                        <td><a id="btnCopyScreen" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                        <td><a id="btnDeleteScreen" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>
            <div class="infoBox">
                <div>A screen is an arrangement of fields that are displayed when the issue is created, edited or transitioned through workflow.</div>
                <div>To choose screens that are displayed when issues are created or edited please map the screens to issue operations using <a href="/yongo/administration/screens/schemes">Screen Schemes</a>.</div>
                <div>To select which screen is displayed for a particular workflow transition, please select the <a href="/yongo/administration/workflows">workflow</a> the transition belongs to and edit it.</div>
            </div>
            <?php if ($screens): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Screen Schemes</th>
                            <th>Workflows</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $screen['id'] ?>">
                                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $screen['id'] ?>"/></td>
                                <td>
                                    <div><a href="/yongo/administration/screen/configure/<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></a></div>
                                    <div class="smallDescription"><?php echo $screen['description'] ?></div>
                                </td>
                                <td>
                                    <?php
                                        $screenSchemes = UbirimiContainer::get()['repository']->get(ScreenScheme::class)->getByScreenId($clientId, $screen['id']);
                                        if ($screenSchemes) {
                                            echo '<ul>';
                                            while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/screen/configure-scheme/' . $screenScheme['id'] . '">' . $screenScheme['name'] . '</a></li>';
                                            }
                                            echo '</ul>';
                                        }
                                    ?>
                                </td>
                                <td width="500px">
                                    <?php
                                        $workflows = UbirimiContainer::get()['repository']->get(Workflow::class)->getByScreen($clientId, $screen['id']);
                                        if ($workflows) {
                                            echo '<ul>';
                                            while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/workflow/view-transition/' . $workflow['workflow_data_id'] . '">' . $workflow['name'] . '</a> (' . $workflow['transition_name'] . ')</li>';
                                            }
                                            echo '</ul>';
                                        }
                                    ?>
                                </td>
                                <?php if ($screenSchemes || $workflows): ?>
                                    <input type="hidden" id="delete_possible_<?php echo $screen['id'] ?>" value="0">
                                <?php else: ?>
                                    <input type="hidden" id="delete_possible_<?php echo $screen['id'] ?>" value="1">
                                <?php endif ?>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <div id="deleteIssueSetting"></div>
                <input type="hidden" value="type" id="setting_type"/>
            <?php else: ?>
                <div class="messageGreen">There are no screens defined defined.</div>
            <?php endif ?>
            <div class="ubirimiModalDialog" id="modalDeleteScreen"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>