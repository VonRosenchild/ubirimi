<?php
    use Ubirimi\Calendar\Repository\EventReminder;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $reminderId = $_POST['id'];

    EventReminder::deleteById($reminderId);