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
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a> > <a class="linkNoUnderline" href="/yongo/administration/issue-security-scheme-levels/' . $issueSecurityScheme['id'] . '">' . $issueSecurityScheme['name'] . '</a> > Level: ' . $level['name'] . ' > Add Data';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="add_level_data" action="/yongo/administration/issue-security-scheme/level-data/add/<?php echo $levelId ?>" method="post">
            <table width="100%">
                <tr>
                    <td></td>
                    <td width="200px">
                        <input class="radio" type="radio" name="type" id="label_project_lead" value="project_lead">
                        <label for="label_project_lead">Project Lead</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_current_assignee" value="current_assignee">
                        <label for="label_current_assignee">Current Assignee</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_reporter" value="reporter">
                        <label for="label_reporter">Reporter</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_user" value="user">
                        <label for="label_user">Single User</label>
                    </td>
                    <td>
                        <select name="user" class="select2Input">
                            <option value>Choose a user</option>
                            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_group" value="group">
                        <label for="label_group">Group</label>
                    </td>
                    <td>
                        <select name="group" class="select2Input">
                            <option value>Choose a group</option>
                            <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_project_role" value="role">
                        <label for="label_project_role">Project Role</label>
                    </td>
                    <td>
                        <select name="role" class="select2Input">
                            <option value>Choose a project role</option>
                            <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $role['id'] ?>"><?php echo $role['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_new_data" class="btn ubirimi-btn">Add</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-security-scheme-levels/<?php echo $issueSecurityScheme['id'] ?>">Cancel</a>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>