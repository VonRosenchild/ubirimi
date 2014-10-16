<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PurgeDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $pageId = $request->get('id');

        $page = $this->getRepository('documentador.entity.entity')->getById($pageId);

        return new Response('This will permanently remove the Page ' . $page['name'] . ' from Documentador. Do you wish to continue?');
    }
}

