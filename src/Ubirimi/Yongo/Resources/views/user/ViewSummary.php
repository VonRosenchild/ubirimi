<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $title = $user['first_name'] . ' ' . $user['last_name'] . ' > Profile';
        Util::renderBreadCrumb($title);
    ?>

    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($userId == $loggedInUserId): ?>
                    <td><a id="btnUserChangePassword" href="#" class="btn ubirimi-btn">Change Password</a></td>
                <?php endif ?>
                <?php if ($hasAdministrationGlobalPermission || $hasSystemAdministrationGlobalPermission): ?>
                    <td><a href="/yongo/administration/user/project-roles/<?php echo $userId ?>" class="btn ubirimi-btn">View Project Roles</a></td>
                <?php endif ?>
                <?php if ($userId == $loggedInUserId): ?>
                    <td><a id="btnChangePreferences" href="#" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Preferences</a></td>
                <?php endif ?>
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
                                             src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($user, 'big') ?>" />
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
                        <?php require_once __DIR__ . '/../../../Resources/views/project/ViewActivityStream.php'; ?>
                    </div>
                </td>
            </tr>
        </table>

        <div class="ubirimiModalDialog" id="modalChangePassword"></div>
        <div class="ubirimiModalDialog" id="modalChangePreferences"></div>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>