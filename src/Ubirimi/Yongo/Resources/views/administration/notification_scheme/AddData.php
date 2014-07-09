<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/notification-schemes">Notification Schemes</a> > <a class="linkNoUnderline" href="/yongo/administration/notification-scheme/edit/<?php echo $notificationSchemeId ?>"><?php echo $notificationScheme['name'] ?></a> > Create Notification
                    </div>
                </td>
                <td align="right">
                    <div align="right">
                        <?php if (isset($backLink)): ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Back</a>
                        <?php else: ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/notification-schemes">Back</a>
                        <?php endif ?>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>

        <div>Please select the type of Notification you wish to add to scheme:</div>
        <form name="add_notification_event_data" action="/yongo/administration/notification-scheme/add-data/<?php echo $notificationSchemeId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="100px" valign="top">Events</td>
                    <td width="200">
                        <select class="inputTextCombo" name="event[]" size="10" multiple="multiple">
                            <?php while ($event = $events->fetch_array(MYSQLI_ASSOC)): ?>
                            <option <?php if ($event['id'] == $eventId) echo 'selected="selected"' ?> value="<?php echo $event['id'] ?>"><?php echo $event['name'] ?></option>
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
                        <input class="radio" type="radio" name="type" id="label_current_user" value="current_user">
                        <label for="label_current_user">Current User</label>
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
                        <input class="radio" type="radio" name="type" id="label_component_lead" value="component_lead">
                        <label for="label_component_lead">Component Lead</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_user" value="user">
                        <label for="label_user">User</label>
                    </td>
                    <td>
                        <select name="user" class="inputTextCombo">
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
                        <select name="group" class="inputTextCombo">
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
                        <select name="role" class="inputTextCombo">
                            <option value>Choose a project role</option>
                            <?php while ($role = $roles->fetch_array(MYSQLI_ASSOC)): ?>
                            <option value="<?php echo $role['id'] ?>"><?php echo $role['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="radio" type="radio" name="type" id="label_all_watchers" value="all_watchers">
                        <label for="label_all_watchers">All Watchers</label>
                    </td>
                    <td>
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
                            <a class="btn ubirimi-btn" href="/yongo/administration/notification-scheme/edit/<?php echo $notificationSchemeId ?>">Cancel</a>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>