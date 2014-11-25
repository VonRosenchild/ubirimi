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
use Ubirimi\Repository\User\UbirimiUser;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/users">Users</a> > <a class="linkNoUnderline" href="/yongo/administration/roles">Project Roles</a> > <?php echo $user['first_name'] . ' ' . $user['last_name'] ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($projects && $roles): ?>
                    <td><a href="/yongo/administration/user/project-roles/edit/<?php echo $userId ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Project Roles</a></td>
                <?php else: ?>
                    <td><a class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit Project Roles</a></td>
                <?php endif ?>
            </tr>
        </table>
        <div class="infoBox">
            <div>A group name (shown in brackets) indicates that the group is a member of the project role, and the user is a member of the group; so the user is an indirect member of the project role.</div>
        </div>
        <?php if (!$roles): ?>
            <div class="infoBox">There are no project roles defined. To add a role click <a href="/yongo/administration/role/add">here</a></div>
        <?php endif ?>

        <?php if ($projects): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Project</th>
                        <?php while ($roles && $role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                            <th><?php echo $role['name'] ?></th>
                        <?php endwhile ?>
                    </tr>
                </thead>
                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $project['id'] ?>">
                        <td width="22"></td>
                        <td><?php echo $project['name'] ?></td>
                        <?php if ($roles) $roles->data_seek(0); ?>
                        <?php while ($roles && $role = $roles->fetch_array(MYSQLI_ASSOC)): ?>

                            <td align="center">
                                <?php

                                    $userIsDirectMemberOfProjectRole = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->checkUserInProjectRoleId($userId, $project['id'], $role['id']);

                                    $groups = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getGroupsForUserIdAndRoleId($userId, $project['id'], $role['id'], $groupIds);
                                ?>
                                <input type="checkbox" disabled="disabled" <?php if ($userIsDirectMemberOfProjectRole)
                                    echo 'checked="checked"'; ?> />
                                <?php $dataArray = array() ?>
                                <?php while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                    <?php $dataArray[] = $group['group_name'] ?>
                                <?php endwhile ?>
                                <?php if (count($dataArray) == 1)
                                    echo '(' . $dataArray[0] . ')'; else echo implode(', ', $dataArray) ?>

                            </td>
                        <?php endwhile ?>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no projects defined yet.</div>
        <?php endif ?>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>