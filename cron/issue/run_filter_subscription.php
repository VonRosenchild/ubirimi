<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

/* check locking mechanism */
if (file_exists('run_filter_subscription.lock')) {
    $fp = fopen('run_filter_subscription.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for run_filter_subscription task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../../web/bootstrap_cli.php';

$filterSubscriptionId = $argv[1];
$filterSubscription = IssueFilter::getSubscriptionById($filterSubscriptionId);
$filter = IssueFilter::getById($filterSubscription['filter_id']);
$definition = $filter['definition'];
$searchParametersInFilter = explode('&', $definition);
$searchParameters = array();
foreach ($searchParametersInFilter as $searchParameter) {
    $data = explode('=', $searchParameter);
    $searchParameters[$data[0]] = $data[1];
}
$user = User::getById($filter['user_id']);
$smtpSettings = SMTPServer::getByClientId($user['client_id']);
$client = Client::getById($user['client_id']);
$subject = $smtpSettings['email_prefix'] . " Filter - " . $filter['name'];

$usersToNotify = array();

if ($filterSubscription['user_id']) {
    $user = User::getById($filterSubscription['user_id']);
    $usersToNotify[] = $user;
} else if ($filterSubscription['group_id']) {
    $users = Group::getDataByGroupId($filterSubscription['group_id']);
    while ($users && $user = $users->fetch_array(MYSQLI_ASSOC)) {
        $usersToNotify[] = $user;
    }
}

foreach ($usersToNotify as $user) {
    $issues = Issue::getByParameters($searchParameters, $filterSubscription['user_id'], null, $filterSubscription['user_id']);

    EmailQueue::add($user['client_id'],
        $smtpSettings['from_address'],
        $user['email'],
        null,
        $subject,
        Util::getTemplate('_filterSubscription.php', array(
                'issues' => $issues,
                'client_domain' => $client['company_domain'])
        ),
        Util::getServerCurrentDateTime());
}

if (null !== $fp) {
    fclose($fp);
}