<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Component;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $componentId = $request->request->get('component_id');
        $component = $this->getRepository(YongoProject::class)->getComponentById($componentId);

        $this->getRepository(IssueComponent::class)->deleteById($componentId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Project Component ' . $component['name'],
            $currentDate
        );

        return new Response('');
    }
}
