<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_GET['id'];

    $page = Entity::getById($pageId);

    echo 'This will permanently remove the Page ' . $page['name'] . ' from Documentador. Do you wish to continue?';