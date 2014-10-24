<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueType;


class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        if ($newId) {
            $projects = $this->getRepository(UbirimiClient::class)->getProjects($session->get('client/id'), 'array', 'id');
            $this->getRepository(Issue::class)->updateType($projects, $oldId, $newId);
        }

        $issueType = IssueType::getById($oldId);
        IssueType::deleteById($oldId);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Type ' . $issueType['name'],
            $currentDate
        );

        return new Response('');
    }
}
