<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $uniqueId = time();

    require_once __DIR__ . '/../../Resources/views/event/AddReminderConfirm.php';