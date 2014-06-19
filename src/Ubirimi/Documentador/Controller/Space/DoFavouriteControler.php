<?php

    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $spaceId = $_POST['id'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Space::addToFavourites($spaceId, $loggedInUserId, $currentDate);