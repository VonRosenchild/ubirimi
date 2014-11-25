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
                        <a class="linkNoUnderline" href="/yongo/administration/users">Users</a> > <?php echo $user['first_name'] . ' ' . $user['last_name'] ?> > <a class="linkNoUnderline" href="/yongo/administration/roles">Project Roles</a> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_user_project_role" method="post" action="/yongo/administration/user/project-roles/edit/<?php echo $userId ?>">

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Project</th>
                        <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                            <th><?php echo $role['name'] ?></th>
                        <?php endwhile ?>
                    </tr>
                </thead>
                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $project['id'] ?>">
                        <td width="22"></td>
                        <td><?php echo $project['name'] ?></td>
                        <?php $roles->data_seek(0) ?>
                        <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                        <td align="center">
                            <?php

                                $userIsDirectMemberOfProjectRole = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->checkUserInProjectRoleId($userId, $project['id'], $role['id']);

                                $groups = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getGroupsForUserIdAndRoleId($userId, $project['id'], $role['id'], $groupIds);
                            ?>
                            <input name="role_<?php echo $project['id'] . '_' . $role['id'] ?>" type="checkbox" <?php if ($userIsDirectMemberOfProjectRole) echo 'checked="checked"'; ?> />
                            <?php $dataArray = array() ?>
                            <?php while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                <?php $dataArray[] = $group['group_name'] ?>
                            <?php endwhile ?>
                            <?php if (count($dataArray) == 1) echo '(' . $dataArray[0] . ')'; else echo implode(', ', $dataArray) ?>
                        </td>
                        <?php endwhile ?>
                    </tr>
                <?php endwhile ?>
            </table>
            <br />
            <div align="left">
                <button type="submit" name="edit_user_project_role" class="btn ubirimi-btn"><i class="icon-edit"></i> Update User Project Roles</button>
                <a class="btn ubirimi-btn" href="/yongo/administration/user/project-roles/<?php echo $userId ?>">Cancel</a>
            </div>
        </form>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>" />
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>