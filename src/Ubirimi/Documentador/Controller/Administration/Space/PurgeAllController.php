<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $spaceId = $_POST['id'];

    Space::deleteAllFromTrash($spaceId);