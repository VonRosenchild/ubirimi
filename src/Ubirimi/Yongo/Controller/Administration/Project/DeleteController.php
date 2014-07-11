<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Log;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->request->get('project_id');
        $projectDeleted = Project::getById($projectId);

        Project::deleteById($projectId);

        if ($projectId == $session->get('selected_project_id')) {
            $session->set('selected_project_id', null);
        }

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Project ' . $projectDeleted['name'],
            $currentDate
        );

        return new Response('');
    }
}