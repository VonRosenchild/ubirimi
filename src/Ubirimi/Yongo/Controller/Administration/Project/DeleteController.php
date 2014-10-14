<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;


class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->request->get('project_id');
        $projectDeleted = $this->getRepository('yongo.project.project')->getById($projectId);

        $this->getRepository('yongo.project.project')->deleteById($projectId);

        if ($projectId == $session->get('selected_project_id')) {
            $session->set('selected_project_id', null);
        }

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Project ' . $projectDeleted['name'],
            $currentDate
        );

        return new Response('');
    }
}