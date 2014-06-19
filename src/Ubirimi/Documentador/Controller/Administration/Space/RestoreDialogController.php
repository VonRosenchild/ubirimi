<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_GET['id'];

    $page = Entity::getById($pageId);

    echo 'This will restore the Page <b>' . $page['name'] . '</b> back into Documentador. Do you wish to continue?';