<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Status;

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

        $Id = $request->request->get('id');
        $status = $this->getRepository('yongo.issue.settings')->getById($Id, 'status');
        $this->getRepository('yongo.issue.settings')->deleteStatusById($Id);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Status ' . $status['name'],
            $currentDate
        );

        return new Response('');
    }
}
