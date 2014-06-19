<?php
    use Ubirimi\Repository\HelpDesk\Organization;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'helpdesk_organizations';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Administration / Organizations';

    $organizations = Organization::getByClientId($clientId);

    require_once __DIR__ . '/../../../Resources/views/administration/organization/ListOrganization.php';