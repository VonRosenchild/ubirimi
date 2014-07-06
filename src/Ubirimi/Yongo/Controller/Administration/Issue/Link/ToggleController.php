<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    Client::toggleIssueLinkingFeature($clientId);

    $session->set('yongo/settings/issue_linking_flag', 1 - $session->get('yongo/settings/issue_linking_flag'));
    $logText = 'Activate';
    if (0 == $session->get('yongo/settings/issue_linking_flag')) {
        $logText = 'Deactivate';
    }
    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, $logText . ' Yongo Issue Linking', $currentDate);

    header('Location: /yongo/administration/issue-features/linking');