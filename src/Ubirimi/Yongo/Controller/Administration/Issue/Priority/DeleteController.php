<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Priority;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;


use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $oldId = $request->request->get('id');
        $newId = $request->request->get('new_id');

        $priority = Settings::getById($oldId, 'priority');
        $projects = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), 'array', 'id');
        $this->getRepository('yongo.issue.issue')->updatePriority($projects, $oldId, $newId);

        Settings::deletePriorityById($oldId);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Priority ' . $priority['name'],
            $currentDate
        );

        return new Response('');
    }
}
