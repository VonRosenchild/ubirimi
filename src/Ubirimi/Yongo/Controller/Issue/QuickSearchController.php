<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();
    $code = $_POST['code'];

    $issue = Issue::getByParameters(array('project' => $session->get('selected_project_id'), 'nr' => $code), $loggedInUserId);

    if ($issue)
        echo $issue['id'];
    else
        echo 'error';