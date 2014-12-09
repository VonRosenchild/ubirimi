<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

/* check locking mechanism */
if (file_exists(__DIR__ . '/run_filter_subscription.lock')) {
    $fp = fopen('run_filter_subscription.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for run_filter_subscription task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../../web/bootstrap_cli.php';

$filterSubscriptionId = $argv[1];
$filterSubscription = UbirimiContainer::get()['repository']->get(IssueFilter::class)->getSubscriptionById($filterSubscriptionId);
$filter = UbirimiContainer::get()['repository']->get(IssueFilter::class)->getById($filterSubscription['filter_id']);
$definition = $filter['definition'];
$searchParametersInFilter = explode('&', $definition);
$searchParameters = array();
foreach ($searchParametersInFilter as $searchParameter) {
    $data = explode('=', $searchParameter);
    $searchParameters[$data[0]] = $data[1];
}
$user = UbirimiContainer::get()['respository']->get(UbirimiUser::class)->getById($filter['user_id']);
$smtpSettings = UbirimiContainer::get()['repository']->get(SMTPServer::class)->getByClientId($user['client_id']);
$clientSettings = UbirimiContainer::get()['respository']->get(UbirimiClient::class)->getSettings($user['client_id']);

$client = UbirimiContainer::get()['respository']->get(UbirimiClient::class)->getById($user['client_id']);
$subject = $smtpSettings['email_prefix'] . " Filter - " . $filter['name'];

$usersToNotify = array();

if ($filterSubscription['user_id']) {
    $user = UbirimiContainer::get()['respository']->get(UbirimiUser::class)->getById($filterSubscription['user_id']);
    $usersToNotify[] = $user;
} else if ($filterSubscription['group_id']) {
    $users = UbirimiContainer::get()['respository']->get(UbirimiGroup::class)->getDataByGroupId($filterSubscription['group_id']);
    while ($users && $user = $users->fetch_array(MYSQLI_ASSOC)) {
        $usersToNotify[] = $user;
    }
}

foreach ($usersToNotify as $user) {
    $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters($searchParameters, $filterSubscription['user_id'], null, $filterSubscription['user_id']);

    $columns = explode('#', $user['issues_display_columns']);

    UbirimiContainer::get()['repository']->get(EmailQueue::class)->oadd($user['client_id'],
        $smtpSettings['from_address'],
        $user['email'],
        null,
        $subject,
        Util::getTemplate('_filterSubscription.php', array(
                'issues' => $issues,
                'searchParameters' => $searchParameters,
                'clientSettings' => $clientSettings,
                'columns' => $columns,
                'userId' => $user['id'],
                'clientId' => $user['client_id'],
                'cliMode' => true,
                'client_domain' => $client['company_domain'])
        ),
        Util::getServerCurrentDateTime());
}

if (null !== $fp) {
    fclose($fp);
}