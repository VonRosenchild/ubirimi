<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;

Util::checkUserIsLoggedInAndRedirect();

$searchQuery = $_POST['code'];

$clientId = $session->get('client/id');
$loggedInUserId = $session->get('user/id');

$projects = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');
$projects = Util::array_column($projects, 'id');

// search first for a perfect match
$issueResult = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('project' => $projects, 'code_nr' => $searchQuery), $loggedInUserId, null, $loggedInUserId);

if ($issueResult) {
    $issue = $issueResult->fetch_array(MYSQLI_ASSOC);
    echo $issue['id'];
} else {
    echo '-1';
}