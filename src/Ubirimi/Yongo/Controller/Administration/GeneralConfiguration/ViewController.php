<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $clientYongoSettings = Client::getYongoSettings($clientId);
    $menuSelectedCategory = 'system';

    require_once __DIR__ . '/../../../Resources/views/administration/general_configuration/View.php';