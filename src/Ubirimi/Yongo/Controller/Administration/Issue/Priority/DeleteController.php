<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Priority;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;


class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        $priority = $this->getRepository(IssueSettings::class)->getById($oldId, 'priority');
        $projects = $this->getRepository(UbirimiClient::class)->getProjects($session->get('client/id'), 'array', 'id');
        $this->getRepository(Issue::class)->updatePriority($projects, $oldId, $newId);

        $this->getRepository(IssueSettings::class)->deletePriorityById($oldId);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Priority ' . $priority['name'],
            $currentDate
        );

        return new Response('');
    }
}
