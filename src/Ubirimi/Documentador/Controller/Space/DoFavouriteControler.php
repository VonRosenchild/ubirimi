<?php


    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $spaceId = $_POST['id'];

    $currentDate = Util::getServerCurrentDateTime();
    Space::addToFavourites($spaceId, $loggedInUserId, $currentDate);