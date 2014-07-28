<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewProjectSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');

        $project = Project::getById($projectId);

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / ' . $project['name'];

        $session->set('selected_project_id', $projectId);
        $menuSelectedCategory = 'project';

        $menuProjectCategory = 'summary';

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ViewProjectSummary.php', get_defined_vars());
    }
}
