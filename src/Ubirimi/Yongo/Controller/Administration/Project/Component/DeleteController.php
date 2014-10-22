<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Component;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $componentId = $request->request->get('component_id');
        $component = $this->getRepository('yongo.project.project')->getComponentById($componentId);

        $this->getRepository('yongo.issue.component')->deleteById($componentId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Project Component ' . $component['name'],
            $currentDate
        );

        return new Response('');
    }
}
