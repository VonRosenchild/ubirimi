<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_GET['user_id'];
    $delete_own_user = false;
    if ($userId == $session->get('user/id'))
        $delete_own_user = true;

    if (!$delete_own_user) {
        $issues_reported_count = 0;
        $issues_assigned_count = 0;

        $user = User::getById($userId);
        $issuesReported = Issue::getByParameters(array('reporter' => $userId));
        $issuesAssigned = Issue::getByParameters(array('assignee' => $userId));

        if (null != $issuesReported)
            $issues_reported_count = $issuesReported->num_rows;

        if (null != $issuesAssigned)
            $issues_assigned_count = $issuesAssigned->num_rows;
    }
?>
<?php if ($delete_own_user): ?>
    <div>You can not delete the logged in user.</div>
    <input type="hidden" value="0" id="delete_possible" />
<?php else: ?>
    <?php if ($issues_reported_count || $issues_assigned_count): ?>
        <div>You are not able to delete this user due to the following reasons:</div>
        <div>Issues reported by this user: <?php echo $issues_reported_count ?></div>
        <div>Issues assigned to this user: <?php echo $issues_assigned_count ?></div>
        <input type="hidden" value="0" id="delete_possible" />
    <?php else: ?>
        <div>Are you sure you want to delete <b><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></b>?</div>
        <div>Keep in mind that any project components where this user is set as lead will be left with out a leader.</div>
        <input type="hidden" value="1" id="delete_possible" />
    <?php endif ?>
<?php endif ?>