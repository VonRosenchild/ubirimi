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
use Ubirimi\Yongo\Repository\Issue\IssueEvent;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Events') ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/attachment-configuration">Attachments</a></li>
            <li class="active"><a href="/yongo/administration/events">Events</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/add-event" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Event</a></td>
                <td><a id="btnEditEvent" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteEvent" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Associated Notification Schemes</th>
                    <th>Associated Workflows</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $events->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $event['id'] ?>">
                        <td width="22">
                            <input <?php if ($event['system_flag']) echo 'disabled="disabled"' ?> type="checkbox" value="1" id="el_check_<?php echo $event['id'] ?>" />
                        </td>
                        <td>
                            <?php
                                echo $event['name'];
                                if ($event['system_flag'] == 1) echo ' (System)';
                            ?>
                        </td>
                        <td><?php echo $event['description'] ?></td>
                        <td>
                            <?php
                                $canBeDeleted = 1;

                                $notificationSchemes = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getNotificationSchemesByEventId($clientId, $event['id']);
                                if ($notificationSchemes) {
                                    $canBeDeleted = 0;
                                    echo '<ul>';
                                    while ($notificationScheme = $notificationSchemes->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<li><a href="/yongo/administration/notification-scheme/edit/' . $notificationScheme['id'] . '">' . $notificationScheme['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                $workflows = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getWorkflowsByEventId($clientId, $event['id']);
                                if ($workflows) {
                                    $canBeDeleted = 0;
                                    echo '<ul>';
                                    while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<li><a href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                        <input type="hidden" value="<?php echo $canBeDeleted ?>" id="delete_possible_<?php echo $event['id'] ?>" />
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteEvent"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>