<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RestoreDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $pageId = $request->get('id');

        $page = $this->getRepository(Entity::class)->getById($pageId);

        return new Response('This will restore the Page <b>' . $page['name'] . '</b> back into Documentador. Do you wish to continue?');
    }
}