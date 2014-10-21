<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Resolution;

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

        $resolution = $this->getRepository('yongo.issue.settings')->getById($oldId, 'resolution');
        $projects = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), 'array', 'id');
        $this->getRepository('yongo.issue.issue')->updateResolution($projects, $oldId, $newId);

        $this->getRepository('yongo.issue.settings')->deleteResolutionById($oldId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Resolution ' . $resolution['name'],
            $currentDate
        );

        return new Response('');
    }
}
