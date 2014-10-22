<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListProjectController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = $session->get('client/settings');
        $projects = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), null, null, true);

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Projects';

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ListProject.php', get_defined_vars());
    }
}