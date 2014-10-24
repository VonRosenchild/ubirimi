<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Project\YongoProject;

$selectedProductId = $session->get('selected_product_id');
?>
<table width="100%" id="contentDetails">
    <tr>
        <td valign="top" width="320">
            <!--            main data-->
            <table width="100%" id="details1">
                <tr>
                    <td width="120">
                        <div class="textLabel">Priority:</div>
                    </td>
                    <td>
                        <img title="<?php echo $issue['priority_name'] . ' - ' . $issue['issue_priority_description'] ?>" height="16px" src="/yongo/img/issue_priority/<?php echo $issue['issue_priority_icon_name'] ?>"/>
                        <?php echo $issue['priority_name'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="textLabel">Type:</div>
                    </td>
                    <td>
                        <img title="<?php echo $issue['type_name'] . ' - ' . $issue['issue_type_description'] ?>" height="16px" src="/yongo/img/issue_type/<?php echo $issue['issue_type_icon_name'] ?>"/>
                        <?php echo $issue['type_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="textLabel">Status:</div>
                    </td>
                    <td colspan="3">
                        <?php echo $issue['status_name'] ?>
                        <?php if ($issue[Field::FIELD_RESOLUTION_CODE]): ?>
                            <span>(<?php echo $issue['resolution_name'] ?>)</span>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top" width="400">
            <!-- users-->
            <table width="100%" id="details2">
                <tr>
                    <td width="120" valign="top">
                        <div class="textLabel">Assignee:</div>
                    </td>
                    <td valign="middle">
                        <?php
                            if ($issue[Field::FIELD_ASSIGNEE_CODE]) {
                                echo '<img width="33px" style="vertical-align: middle;" src="' . UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(array('avatar_picture' => $issue['assignee_avatar_picture'] ,'id' => $issue['assignee']), 'small') . '" />';
                                echo ' ' . LinkHelper::getUserProfileLink($issue[Field::FIELD_ASSIGNEE_CODE], $selectedProductId, $issue['ua_first_name'], $issue['ua_last_name']);
                            } else {
                                echo 'Unassigned';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="80px">
                        <div class="textLabel">Reporter:</div>
                    </td>
                    <td valign="middle">
                        <img width="33px" style="vertical-align: middle;" src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(array('avatar_picture' => $issue['reporter_avatar_picture'] ,'id' => $issue['reporter']), 'small') ?>" />
                            <?php echo LinkHelper::getUserProfileLink($issue[Field::FIELD_REPORTER_CODE], $selectedProductId, $issue['ur_first_name'], $issue['ur_last_name']) ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                        <div class="textLabel">
                            <span>Watchers: </span>
                            <span style="background-color: #d3d3d3; border-radius: 2em; padding: 0px 2px 2px 6px; cursor: pointer;" id="issueWatcherCount">
                                <b><?php if (isset($watchers)) echo $watchers->num_rows; else echo '0' ?></b>
                            </span>
                            &nbsp;
                            <?php if ($loggedInUserId): ?>
                                <?php $loggedInUserIsWatcher = false; ?>
                                <?php while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)): ?>
                                    <?php if ($watcher['id'] == $loggedInUserId): ?>
                                        <a href="#" data="remove" class="toggle_watch_issue">Stop watching this issue</a>
                                        <?php
                                            $loggedInUserIsWatcher = true;
                                            break;
                                        ?>
                                    <?php endif ?>
                                <?php endwhile ?>
                                <?php if (!$loggedInUserIsWatcher): ?>
                                    <a href="#" data="add" class="toggle_watch_issue">Start watching this issue</a>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>

                <?php if ($issue[Field::FIELD_ISSUE_SECURITY_LEVEL_CODE]): ?>
                    <tr>
                        <td>
                            <div class="textLabel">Security Level:</div>
                        </td>
                        <td><?php echo $issue['security_level_name'] ?></td>
                    </tr>
                <?php endif ?>
            </table>
        </td>
        <td valign="top">
            <!--extra data    -->
            <table width="100%" id="details3">
                <tr>
                    <td width="150" valign="top">
                        <div class="textLabel">Components:</div>
                    </td>
                    <td>
                        <?php
                            if ($components) {
                                while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)) {
                                    UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponentsInViewIssue($component, '');
                                }
                            } else {
                                echo 'None';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="textLabel">Affects version/s:</div>
                    </td>
                    <td>
                        <?php $indexVersion = 1 ?>
                        <?php if ($versionsAffected): ?>
                            <?php while ($version = $versionsAffected->fetch_array(MYSQLI_ASSOC)): ?>
                                <span>
                                    <?php
                                        echo LinkHelper::getYongoProjectVersionLink($version['project_version_id'], $version['name']);
                                        if ($indexVersion < $versionsAffected->num_rows) echo ', ';
                                    ?>
                                </span>
                                <?php $indexVersion++ ?>
                            <?php endwhile ?>
                        <?php else: ?>
                            <span>None</span>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="textLabel">Fix Version/s:</div>
                    </td>
                    <td>
                        <?php $indexVersion = 1 ?>
                        <?php if ($versionsTargeted): ?>
                            <?php while ($version = $versionsTargeted->fetch_array(MYSQLI_ASSOC)): ?>
                                <span>
                                    <?php
                                        echo LinkHelper::getYongoProjectVersionLink($version['project_version_id'], $version['name']);
                                        if ($indexVersion < $versionsTargeted->num_rows) echo ', ';
                                    ?>
                                </span>
                                <?php $indexVersion++ ?>
                            <?php endwhile ?>
                        <?php else: ?>
                            <span>None</span>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php if ($customFieldsData || $customFieldsDataUserPickerMultipleUser): ?>
        <?php require_once __DIR__ . '/../../views/issue/_custom_fields_data.php' ?>
    <?php endif ?>
</table>