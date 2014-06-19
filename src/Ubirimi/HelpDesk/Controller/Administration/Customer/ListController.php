<?php
    use Ubirimi\Repository\HelpDesk\Organization;
    use Ubirimi\Repository\HelpDesk\OrganizationCustomer;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $organizationId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($organizationId) {
        $customers = OrganizationCustomer::getByOrganizationId($organizationId);
        $organization = Organization::getById($organizationId);
        $breadCrumbTitle = 'Customers > ' . $organization['name'];
    } else {
        $customers = User::getByClientId($clientId, 1);
        $breadCrumbTitle = 'Customers > All';
    }

    $menuSelectedCategory = 'helpdesk_organizations';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Administration / Customers';


    require_once __DIR__ . '/../../../Resources/views/administration/customer/List.php';