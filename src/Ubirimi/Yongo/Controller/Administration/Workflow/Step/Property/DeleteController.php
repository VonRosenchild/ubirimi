<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step\Property;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $propertyId = $request->request->get('id');

        $this->getRepository('yongo.workflow.workflow')->deleteStepPropertyById($propertyId);

        return new Response('');
    }
}