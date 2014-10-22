<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DashboardController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'home';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

        $projectsForBrowsing = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), null, null, true);

        if ($projectsForBrowsing) {
            $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
            $projectsForBrowsing->data_seek(0);
            $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));
        }

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/Dashboard.php', get_defined_vars());
    }
}
