<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $pageId = $_POST['id'];
    Entity::removeAsFavouriteForUserId($pageId, $loggedInUserId);