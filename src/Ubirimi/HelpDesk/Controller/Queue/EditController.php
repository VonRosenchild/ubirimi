<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $queueId = $_GET['id'];
    $queue = Queue::getById($queueId);
    $projectId = $queue['project_id'];

    $emptyName = false;
    $queueExists = false;

    if (isset($_POST['edit_queue'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $description = Util::cleanRegularInputField($_POST['definition']);

        if (empty($name)) {
            $emptyName = true;
        }

        // check for duplication
        $queue = Queue::getByName($queueId, mb_strtolower($name), $projectId);
        if ($queue) {
            $queueExists = true;
        }

        if (!$queueExists && !$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            Queue::updateById($queueId, $name, $description, $description, $currentDate);

            header('Location: /helpdesk/queues/' . $projectId . '/' . $queueId);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Update Queue';

    require_once __DIR__ . '/../../Resources/views/queue/Edit.php';