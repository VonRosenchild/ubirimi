<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Queue;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');

        $emptyName = false;
        $queueExists = false;

        $queues = Queue::getByProjectId($projectId);
        $selectedQueueId = -1;
        if ($queues) {
            $firstQueue = $queues->fetch_array(MYSQLI_ASSOC);
            $selectedQueueId = $firstQueue['id'];
        }

        if ($request->request->has('new_queue')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $queueDefinition = Util::cleanRegularInputField($request->request->get('definition'));

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

                $queueId = Queue::save(
                    $session->get('user/id'),
                    $projectId,
                    $name,
                    $description,
                    $queueDefinition,
                    $defaultColumns,
                    $currentDate
                );

                return new RedirectResponse('/helpdesk/queues/' . $projectId . '/' . $queueId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Create Queue';

        return $this->render(__DIR__ . '/../../Resources/views/queue/AddQueue.php', get_defined_vars());
    }
}
