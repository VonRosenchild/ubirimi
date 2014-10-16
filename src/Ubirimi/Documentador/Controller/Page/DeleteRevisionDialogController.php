<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteRevisionDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $pageId = $request->get('entity_id');
        $revisionNR = $request->get('rev_nr');
        $page = $this->getRepository('documentador.entity.entity')->getById($pageId);

        return new Response('Are you sure you want to remove revision ' . $revisionNR . ' for ' . $page['name']);
    }
}