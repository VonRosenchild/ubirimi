<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/permission-schemes">Permission Schemes</a> > <a class="linkNoUnderline" href="/yongo/administration/permission-scheme/edit/<?php echo $permissionSchemeId ?>"><?php echo $permissionScheme['name'] ?></a> > Create Permission
                    </div>
                </td>
                <td align="right">
                    <div align="right">
                        <?php if (isset($backLink)): ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Back</a>
                        <?php else: ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/permission-schemes">Back</a>
                        <?php endif ?>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <div>Please select the type of Permission you wish to add to scheme:</div>
        <form name="add_permission_data" action="/yongo/administration/permission-scheme/add-data/<?php echo $permissionSchemeId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="100px" valign="top">Permissions</td>
                    <td width="200">
                        <select class="inputTextCombo" name="permission[]" size="10" multiple="multiple">
                            <?php while ($permission = $permissions->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($permission['id'] == $permissionId) echo 'selected="selected"' ?> value="<?php echo $permission['id'] ?>"><?php echo $permission['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                    <td></td>
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
                        <input class="radio" type="radio" name="type" id="label_project_lead" value="project_lead">
                        <label for="label_project_lead">Project Lead</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_user_permission" value="user">
                        <label for="label_user_permission">User</label>
                    </td>
                    <td>
                        <select name="user" class="select2InputMedium" id="perm_choose_user">
                            <option value>Choose a user</option>
                            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['username'] . ')' ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_group_permission" value="group">
                        <label for="label_group_permission">Group</label>
                    </td>
                    <td>
                        <select name="group" class="select2InputMedium" id="perm_choose_group">
                            <option value>Choose a group</option>
                            <option value="0">Anyone</option>
                            <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_project_role_permission" value="role">
                        <label for="label_project_role_permission">Project Role</label>
                    </td>
                    <td>
                        <select name="role" class="select2InputMedium" id="perm_choose_project_role">
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
                            <a class="btn ubirimi-btn" href="/yongo/administration/permission-scheme/edit/<?php echo $permissionSchemeId ?>">Cancel</a>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>