<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <div class="pageContent">
        <?php
            $title = $user['first_name'] . ' ' . $user['last_name'] . ' > Profile';
            Util::renderBreadCrumb($title);
        ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnUserChangePassword" href="#" class="btn ubirimi-btn">Change Password</a></td>
                <?php if ($hasAdministrationGlobalPermission || $hasSystemAdministrationGlobalPermission): ?>
                    <td><a href="/yongo/administration/user/project-roles/<?php echo $userId ?>" class="btn ubirimi-btn">View Project Roles</a></td>
                <?php endif ?>
                <td><a id="btnChangePreferences" href="#" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Preferences</a></td>
                <td>
                    <div class="btn-group">
                        <a href="#" class="btn ubirimi-btn dropdown-toggle" data-toggle="dropdown">
                            Filters <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/yongo/issue/search?assignee=<?php echo $userId ?>&project=<?php echo implode('|', $projectIds) ?>">Assigned</a>
                            </li>
                            <li>
                                <a href="/yongo/issue/search?assignee=<?php echo $userId ?>&project=<?php echo implode('|', $projectIds) ?>&resolution=-2">Assigned & Open</a>
                            </li>
                            <li>
                                <a href="/yongo/issue/search?reporter=<?php echo $userId ?>&project=<?php echo implode('|', $projectIds) ?>">Reported</a>
                            </li>
                            <li>
                                <a href="/yongo/issue/search?reporter=<?php echo $userId ?>&project=<?php echo implode('|', $projectIds) ?>&resolution=-2">Reported & Open</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>

        <div class="messageGreen" id="userDataUpdated"></div>

        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <td valign="top" width="40%">
                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">User details</span></td>
                        </tr>
                        <tr>
                            <td width="200px;" valign="top">
                                <div class="textLabel">Picture</div>
                            </td>
                            <td style="height: 150px;">

                                <span class="fileinput-button picture_user_avatar">
                                    <span>
                                        <img id="profile-picture"
                                             style="width: 150px; height: 150px; vertical-align: top"
                                             title="<?php echo $user['first_name'] . ' ' . $user['last_name'] ?>"
                                             src="<?php echo User::getUserAvatarPicture($user, 'big') ?>" />
                                        <img id="loading" style="display: none" src="/img/loader.gif" />
                                    </span>
                                    <?php if ($loggedInUserId == $userId): ?>
                                        <input id="fileupload" type="file" name="files[]" multiple>
                                    <?php endif ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Full Name:</div>
                            </td>
                            <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textLabel">Username:</div>
                            </td>
                            <td><?php echo $user['username'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textLabel">Email address:</div>
                            </td>
                            <td><?php echo $user['email'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textLabel">Groups:</div>
                            </td>
                            <td>
                                <?php if ($groups): ?>
                                    <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                        <?php $groups_arr[] = $group['name'] ?>
                                    <?php endwhile ?>
                                    <span><?php echo implode($groups_arr, ', ') ?></span>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textLabel">Country:</div>
                            </td>
                            <td>
                                <?php echo $user['country_name'] ?>
                            </td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Preferences</span></td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Issues per page:</div>
                            </td>
                            <td><?php echo $user['issues_per_page'] ?></td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Notify own changes:</div>
                            </td>
                            <td><?php if ($user['notify_own_changes_flag'])
                                    echo 'YES'; else echo 'NO'; ?></td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Unresolved Issues By Project</span></td>
                        </tr>
                        <?php if ($stats): ?>
                            <?php while ($row = $stats->fetch_array(MYSQLI_ASSOC)): ?>
                                <?php if ($row['name']): ?>
                                    <tr>
                                        <td>
                                            <?php echo LinkHelper::getYongoIssueListPageLink($row['name'], array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'resolution' => '-2', 'assignee' => $userId, 'project' => $row['project_id'], 'sort_order' => 'desc')); ?>
                                        </td>
                                        <td><?php echo $row['total'] ?></td>
                                    </tr>
                                <?php endif ?>
                            <?php endwhile ?>
                        <?php else: ?>
                            <tr>
                                <td>No information available</td>
                            </tr>
                        <?php endif ?>
                    </table>
                </td>
                <td width="10px"></td>
                <td valign="top">
                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Activity Stream</span></td>
                        </tr>
                    </table>
                    <div id="user_activity_stream">
                        <?php
                            $activityStream = User::getYongoActivityStream($userId);
                            $structuredData = array();
                            $toDisplay = '';
                            if ($activityStream) {

                                while ($stream = $activityStream->fetch_array(MYSQLI_ASSOC)) {
                                    $formattedDate = date('F d', strtotime($stream['date_created']));
                                    $activityType = $stream['activity_type'];
                                    if (!isset($structuredData[$formattedDate][$stream['user_id']][$stream['issue_id']]))
                                        $structuredData[$formattedDate][$stream['user_id']][$stream['issue_id']][$activityType] = array();

                                    $structuredData[$formattedDate][$stream['user_id']][$stream['issue_id']][$activityType][] = array($stream['field'], $stream['new_value'], $stream['first_name'], $stream['last_name'], $stream['nr'], $stream['code'], $stream['date_created']);
                                }
                                echo '<table width="100%">';
                                foreach ($structuredData as $date => $value) {

                                    $toDisplay .= '<tr>';
                                    $toDisplay .= '<td><b>' . $date . '</b></td>';
                                    $toDisplay .= '</tr>';
                                    foreach ($value as $userId => $issueData) {
                                        foreach ($issueData as $issueId => $issueIdData) {

                                            foreach ($issueIdData as $activityTypeCode => $issueActivityData) {
                                                if ($activityTypeCode == 'issue_creation') {

                                                    $toDisplay .= '<tr>';
                                                        $toDisplay .= '<td>';
                                                        $toDisplay .= '<img width="33px" style="vertical-align: middle;" src="' . User::getUserAvatarPicture(array('avatar_picture' => $user['avatar_picture'],'id' => $userId), 'small') . '" /> ';
                                                        $toDisplay .= LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $issueActivityData[0][2], $issueActivityData[0][3]) . ' created ' . LinkHelper::getYongoIssueViewLink($issueId, $issueActivityData[0][4], $issueActivityData[0][5]) . ' at ' . date('H:i', strtotime($issueActivityData[0][6]));
                                                        $toDisplay .= '</td>';
                                                    $toDisplay .= '</tr>';
                                                } else if ($activityTypeCode == 'issue_history') {

                                                    $toDisplay .= '<tr>';
                                                        $toDisplay .= '<td>';
                                                        $toDisplay .= '<img width="33px" style="vertical-align: middle;" src="' . User::getUserAvatarPicture(array('avatar_picture' => $user['avatar_picture'],'id' => $userId), 'small') . '" /> ';
                                                        $toDisplay .= LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $issueActivityData[0][2], $issueActivityData[0][3]) . ' updated ' . count($issueActivityData) . ' fields of ' . LinkHelper::getYongoIssueViewLink($issueId, $issueActivityData[0][4], $issueActivityData[0][5]) .  ' at ' . date('H:i', strtotime($issueActivityData[0][6]));
                                                        $toDisplay .= '</td>';
                                                    $toDisplay .= '</tr>';

                                                    $toDisplay .= '<tr>';
                                                        $toDisplay .= '<td>';
                                                    $toDisplay .= '<ul>';

                                                    for ($k = 0; $k < count($issueActivityData); $k++) {

                                                        $valueField = $issueActivityData[$k][1];
                                                        $fieldCode = $issueActivityData[$k][0];
                                                        if ($fieldCode == 'time_spent' || $fieldCode == 'remaining_estimate') {
                                                            $valueField = Util::transformTimeToString(Util::transformLogTimeToMinutes($valueField, $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek);
                                                        }

                                                        if ($fieldCode) {
                                                            if (isset(Field::$fieldTranslation[$fieldCode])) {
                                                                $fieldChanged = Field::$fieldTranslation[$fieldCode];
                                                            } else {
                                                                $fieldChanged = $fieldCode;
                                                            }
                                                            if ($valueField != null)
                                                                $toDisplay .= '<li>Changed ' . $fieldChanged . ' to ' . $valueField . '</li>';
                                                            else
                                                                $toDisplay .= '<li>Removed ' . $fieldChanged . '</li>';
                                                        }
                                                    }
                                                    $toDisplay .= '</ul>';
                                                    $toDisplay .= '</td>';
                                                    $toDisplay .= '</tr>';
                                                }
                                            }
                                        }
                                    }
                                }
                                echo $toDisplay;
                                echo '</table>';
                            } else {
                                echo '<div class="infoBox">No available information</div>';
                            }
                        ?>
                    </div>
                </td>
            </tr>
        </table>

        <div id="modalChangePassword"></div>
        <div id="modalChangePreferences"></div>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>