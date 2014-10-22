<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'customer_portal';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Customer Portal / Settings';

        return $this->render(__DIR__ . '/../../../Resources/views/customer_portal/administration/Settings.php', get_defined_vars());
    }
}
