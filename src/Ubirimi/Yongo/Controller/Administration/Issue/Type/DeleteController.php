<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        if ($newId) {
            $projects = Client::getProjects($session->get('client/id'), 'array', 'id');
            Issue::updateType($projects, $oldId, $newId);
        }

        $issueType = IssueType::getById($oldId);
        IssueType::deleteById($oldId);

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Type ' . $issueType['name'],
            $currentDate
        );

        return new Response('');
    }
}
