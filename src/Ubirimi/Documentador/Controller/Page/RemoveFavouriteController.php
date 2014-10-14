<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $pageId = $_POST['id'];
    Entity::removeAsFavouriteForUserId($pageId, $loggedInUserId);