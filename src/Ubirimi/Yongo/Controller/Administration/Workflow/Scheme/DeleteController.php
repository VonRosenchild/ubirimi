<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Scheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Scheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $workflowScheme = Scheme::getMetaDataById($Id);
        Scheme::deleteDataByWorkflowSchemeId($Id);
        Scheme::deleteById($Id);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Workflow Scheme ' . $workflowScheme['name'],
            $currentDate
        );

        return new Response('');
    }
}
