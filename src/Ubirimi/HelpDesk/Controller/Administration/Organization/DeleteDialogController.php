<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    require_once __DIR__ . '/../../../Resources/views/administration/organization/DeleteDialog.php';