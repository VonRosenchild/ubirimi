<?php

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UpdateDisplayColumnsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $queueId = $request->request->get('id');
        $columns = $request->request->get('data');

        $this->getRepository(Queue::class)->updateColumns($queueId, $columns);

        return new RedirectResponse('');
    }
}
