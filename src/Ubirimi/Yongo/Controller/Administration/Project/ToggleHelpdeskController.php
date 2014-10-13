<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;

class ToggleHelpdeskController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $projectId = $request->get('id');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $currentDate = Util::getServerCurrentDateTime();

        if ($project['help_desk_enabled_flag'] == 1) {
            // disable
            $this->getRepository('yongo.project.project')->removeHelpdeskData($projectId);
        } else {
            // enable
            $this->getRepository('yongo.project.project')->addDefaultInitialDataForHelpDesk($clientId, $projectId, $loggedInUserId, $currentDate);
        }

        $this->getRepository('yongo.project.project')->toggleHelpDeskFlag($projectId);

        return new RedirectResponse('/yongo/administration/project/helpdesk/' . $projectId);
    }
}