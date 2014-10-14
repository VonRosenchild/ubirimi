<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $date = Util::getServerCurrentDateTime();

    Entity::addFavourite($pageId, $loggedInUserId, $date);