<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $queueId = $_POST['id'];
    $columns = $_POST['data'];

    Queue::updateColumns($queueId, $columns);