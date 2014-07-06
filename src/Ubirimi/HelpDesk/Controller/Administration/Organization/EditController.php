<?php
    use Ubirimi\Repository\HelpDesk\Organization;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $duplicateOrganization = false;

    $organizationId = $_GET['id'];
    $organization = Organization::getById($organizationId);

    if (isset($_POST['edit_organization'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $organizationDuplicate = Organization::getByName($clientId, mb_strtolower($name), $organizationId);

        if ($organizationDuplicate) {
            $duplicateOrganization = true;
        }

        if (!$emptyName && !$organizationDuplicate) {

            $currentDate = Util::getServerCurrentDateTime();
            Organization::updateById($organizationId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_HELP_DESK, $loggedInUserId, 'UPDATE Organization ' . $name, $currentDate);

            header('Location: /helpdesk/administration/organizations');
        }
    }

    $menuSelectedCategory = 'helpdesk_organizations';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME. ' / Create Organization';

    require_once __DIR__ . '/../../../Resources/views/administration/organization/EditOrganization.php';