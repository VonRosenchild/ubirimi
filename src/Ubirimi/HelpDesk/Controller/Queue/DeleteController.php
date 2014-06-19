<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $queueId = $_POST['id'];
    $projectId = $_POST['project_id'];

    Queue::deleteById($queueId);

    $queues = Queue::getByProjectId($projectId);
    $queueToGoId = -1;
    if ($queues) {
        $firstQueue = $queues->fetch_array(MYSQLI_ASSOC);
        $queueToGoId = $firstQueue['id'];
    }

    echo '/helpdesk/queues/' . $projectId . '/' . $queueToGoId;