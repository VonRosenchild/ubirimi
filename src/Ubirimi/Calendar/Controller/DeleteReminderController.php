<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $reminderId = $_POST['id'];

    Calendar::deleteReminderById($reminderId);