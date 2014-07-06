<?php

    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;

    Util::checkUserIsLoggedInAndRedirect();

    $eventId = $_POST['id'];

    $event = IssueEvent::getById($eventId);

    IssueEvent::deleteById($eventId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Event ' . $event['name'], $currentDate);
