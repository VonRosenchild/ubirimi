<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Issue\Watcher;

$issueId = $_POST['id'];

$issueData = UbirimiContainer::getRepository('yongo.issue.issue')->getByIdSimple($issueId);

$watchers = Watcher::getByIssueId($issueId);
// todo: users watchers de aici trebuie sa fie useri ce au permisiune de browsing la proiectul acesta
$users = Client::getUsers($clientId);
$watcherArray = array();

if ($watchers) {
    while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
        $watcherArray[] = $watcher['id'];
    }
    $watchers->data_seek(0);
}

$hasViewVotersAndWatchersPermission = Project::userHasPermission($issueData['project_id'], Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $loggedInUserId);
$hasManageWatchersPermission = Project::userHasPermission($issueData['project_id'], Permission::PERM_MANAGE_WATCHERS, $loggedInUserId);
?>
<?php if ($hasViewVotersAndWatchersPermission): ?>
    <?php if ($hasManageWatchersPermission): ?>
        <table width="100%">
            <thead>
                <tr>
                    <th align="left">
                        <span>Add Watcher</span>
                    </th>
                    <th valign="top" align="right">
                        <img style="cursor: pointer" id="closeWatchedDialog" width="20px" src="/img/close.png" />
                    </th>
                </tr>
            </thead>

            <tr>
                <td colspan="2">
                    <select style="width: 100%" name="user_to_watch[]" id="user_to_watch" class="inputTextCombo select2Input" multiple="multiple">
                        <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php if (!in_array($user['id'], $watcherArray)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                            <?php endif ?>
                        <?php endwhile ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php endif ?>
    <?php if (!$watchers): ?>
        <div>There are no watchers.</div>
    <?php else: ?>

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="padding-left: 0" colspan="2"><span>Current watchers</span></th>
                    </tr>
                </thead>
            </table>
        <div style="max-height: 150px; overflow-y: auto;">
            <table class="table table-hover table-condensed">
                <tbody>
                    <?php while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td style="padding-left: 0">
                                <?php echo $watcher['first_name'] . ' ' . $watcher['last_name'] ?>
                            </td>
                            <td style="text-align: right" align="right">
                                <?php if ($hasManageWatchersPermission): ?>
                                    <img style="cursor: pointer" id="remove_watcher_<?php echo $watcher['id'] ?>" width="20px" src="/img/close.png" />
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
    <?php if ($hasManageWatchersPermission): ?>
        <div align="right">
            <input class="btn ubirimi-btn" id="add_watcher" type="button" value="Add Watchers" />
        </div>
    <?php endif ?>
<?php endif ?>