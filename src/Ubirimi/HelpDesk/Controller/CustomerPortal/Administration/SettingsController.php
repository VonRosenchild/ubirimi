<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Project\Project;

class SettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $project = Project::getById($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'customer_portal';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Customer Portal / Settings';

        return $this->render(__DIR__ . '/../../../Resources/views/customer_portal/administration/Settings.php', get_defined_vars());
    }
}
