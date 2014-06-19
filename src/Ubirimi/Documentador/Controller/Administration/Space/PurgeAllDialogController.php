<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $spaceId = $_GET['id'];

    $pages = Space::getDeletedPages($spaceId);

    echo 'This will remove all ' . $pages->num_rows . ' items permanently. Do you wish to continue?';