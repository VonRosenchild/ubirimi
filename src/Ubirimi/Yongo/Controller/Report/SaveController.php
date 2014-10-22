<?php

namespace Ubirimi\Yongo\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $Id = $request->request->get('id');
        $projectId = $request->request->get('project_id');
        $filterName = $request->request->get('filter_name');
        $filterDescription = $request->request->get('filter_description');
        $filterData = $request->request->get('filter_data');

        $date = Util::getServerCurrentDateTime();

        if ($Id != -1) {
            $this->getRepository('yongo.issue.filter')->updateById($Id, $filterName, $filterDescription, $filterData, $date);
            $Id = -1;
            $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo filter' . $filterName, $date);
        } else {
            $Id = $this->getRepository('yongo.issue.filter')->save($loggedInUserId, $filterName, $filterDescription, $filterData, $date);
            $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo filter' . $filterName, $date);
        }

        return new Response($Id);
    }
}
