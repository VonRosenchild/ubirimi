<?php

    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    $signInError = null;

    $httpHOST = Util::getHttpHost();

    $clientSettings = Client::getSettingsByBaseURL($httpHOST);
    $clientId = $clientSettings['id'];

    $client = Client::getById($clientId);

    $sectionPageTitle = $client['company_name'] . ' - Welcome to Customer Portal';

    require_once __DIR__ . '/../../Resources/views/customer_portal/Index.php';