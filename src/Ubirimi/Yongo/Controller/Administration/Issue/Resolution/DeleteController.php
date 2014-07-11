<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Resolution;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        $resolution = IssueSettings::getById($oldId, 'resolution');
        $projects = Client::getProjects($session->get('client/id'), 'array', 'id');
        Issue::updateResolution($projects, $oldId, $newId);

        IssueSettings::deleteResolutionById($oldId);

        $currentDate = Util::getServerCurrentDateTime();
        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Resolution ' . $resolution['name'],
            $currentDate
        );

        return new Response('');
    }
}
