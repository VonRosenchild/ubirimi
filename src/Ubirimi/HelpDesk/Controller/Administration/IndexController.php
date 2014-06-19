<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'helpdesk_administration';

    require_once __DIR__ . '/../../Resources/views/administration/Index.php';