<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $queueId = $request->get('id');
        $queue = Queue::getById($queueId);
        $projectId = $queue['project_id'];

        $emptyName = false;
        $queueExists = false;

        if ($request->request->has('edit_queue')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $description = Util::cleanRegularInputField($request->request->get('definition'));

            if (empty($name)) {
                $emptyName = true;
            }

            // check for duplication
            $queue = Queue::getByName($queueId, mb_strtolower($name), $projectId);
            if ($queue) {
                $queueExists = true;
            }

            if (!$queueExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                Queue::updateById($queueId, $name, $description, $description, $currentDate);

                return new RedirectResponse('/helpdesk/queues/' . $projectId . '/' . $queueId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Update Queue';

        return $this->render(__DIR__ . '/../../Resources/views/queue/Edit.php', get_defined_vars());
    }
}
