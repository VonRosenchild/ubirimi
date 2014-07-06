<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];

    $emptyName = false;
    $queueExists = false;

    $queues = Queue::getByProjectId($projectId);
    $selectedQueueId = -1;
    if ($queues) {
        $firstQueue = $queues->fetch_array(MYSQLI_ASSOC);
        $selectedQueueId = $firstQueue['id'];
    }

    if (isset($_POST['new_queue'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $queueDefinition = Util::cleanRegularInputField($_POST['definition']);

        if (empty($name)) {
            $emptyName = true;
        }

        // check for duplication
        $queue = Queue::getByName($projectId, mb_strtolower($name));
        if ($queue)
            $queueExists = true;

        if (!$queueExists && !$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();
            $defaultColumns = 'code#summary#priority#status#created#updated#reporter#assignee';

            $queueId = Queue::save($loggedInUserId, $projectId, $name, $description, $queueDefinition, $defaultColumns, $currentDate);

            header('Location: /helpdesk/queues/' . $projectId . '/' . $queueId);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Create Queue';

    require_once __DIR__ . '/../../Resources/views/queue/AddQueue.php';