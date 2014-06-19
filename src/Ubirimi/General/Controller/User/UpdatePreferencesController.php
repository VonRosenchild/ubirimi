<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $userId = $_POST['id'];
    $issuesPerPage = $_POST['issues_per_page'];
    $notifyOwnChangesFlag = $_POST['notify_own_changes'];

    $parameters = array(array('field' => 'issues_per_page', 'value' => $issuesPerPage, 'type' => 'i'),
        array('field' => 'notify_own_changes_flag', 'value' => $notifyOwnChangesFlag, 'type' => 'i'));

    User::updatePreferences($userId, $parameters);
    $session->set('user/issues_per_page', $issuesPerPage);