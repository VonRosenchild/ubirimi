<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $eventId = $_GET['id'];
    $deletePossible = $_GET['delete_possible'];
    if ($deletePossible) {
        echo 'Are you sure you want to delete this event?';
    } else {
        echo 'This event can not be deleted.';
        echo '<div>It is associated with a permission scheme or workflow.</div>';
    }