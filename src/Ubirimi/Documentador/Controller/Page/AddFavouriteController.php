<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    Entity::addFavourite($pageId, $loggedInUserId, $date);