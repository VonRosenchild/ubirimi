<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $queueId = $request->request->get('id');
        $projectId = $request->request->get('project_id');

        Queue::deleteById($queueId);

        $queues = Queue::getByProjectId($projectId);
        $queueToGoId = -1;

        if ($queues) {
            $firstQueue = $queues->fetch_array(MYSQLI_ASSOC);
            $queueToGoId = $firstQueue['id'];
        }

        return new Response('/helpdesk/queues/' . $projectId . '/' . $queueToGoId);
    }
}
