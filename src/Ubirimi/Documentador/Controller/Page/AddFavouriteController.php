<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $date = Util::getServerCurrentDateTime();

    Entity::addFavourite($pageId, $loggedInUserId, $date);