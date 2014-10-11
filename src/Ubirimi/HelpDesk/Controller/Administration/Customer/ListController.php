<?php

namespace Ubirimi\HelpDesk\Controller\Administration\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Organization;
use Ubirimi\Repository\HelpDesk\Customer;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $organizationId = $request->query->get('id');

        if ($organizationId) {
            $customers = Customer::getByOrganizationId($organizationId);
            $organization = Organization::getById($organizationId);
            $breadCrumbTitle = 'Customers > ' . $organization['name'];
        } else {
            $customers = User::getByClientId($session->get('client/id'), 1);
            $breadCrumbTitle = 'Customers > All';
        }

        $menuSelectedCategory = 'helpdesk_organizations';

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Administration / Customers';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/customer/List.php', get_defined_vars());
    }
}
