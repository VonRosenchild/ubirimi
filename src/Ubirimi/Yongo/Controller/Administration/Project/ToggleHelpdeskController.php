<?php

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\SystemProduct;
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
        $project = Project::getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $currentDate = Util::getServerCurrentDateTime();

        if ($project['help_desk_enabled_flag'] == 1) {
            // disable
            Project::removeHelpdeskData($projectId);
        } else {
            // enable
            Project::addDefaultInitialDataForHelpDesk($clientId, $projectId, $loggedInUserId, $currentDate);
        }

        Project::toggleHelpDeskFlag($projectId);

        return new RedirectResponse('/yongo/administration/project/helpdesk/' . $projectId);
    }
}