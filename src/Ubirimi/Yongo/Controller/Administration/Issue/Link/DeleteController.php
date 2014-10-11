<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\LinkType;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sourceLinkTypeId = $request->request->get('id');
        $targetLinkTypeId = $request->request->get('new_id');
        $action = $request->request->get('action');

        if ($action == 'swap') {
            LinkType::updateLinkTypeId($sourceLinkTypeId, $targetLinkTypeId);
            LinkType::deleteById($sourceLinkTypeId);
        } else if ($action == 'remove' || $action == null) {
            LinkType::deleteLinksByLinkTypeId($sourceLinkTypeId);
            LinkType::deleteById($sourceLinkTypeId);
        }

        return new Response('');
    }
}
