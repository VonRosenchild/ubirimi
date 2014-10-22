<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UpdateParentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $entityId = $request->request->get('entity_id');
        $parentId = $request->request->get('parent_id');

        $this->getRepository('documentador.entity.entity')->updateParent($entityId, $parentId);

        return new Response('');
    }
}