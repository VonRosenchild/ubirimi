<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'system';
    $clientSettings = Client::getYongoSettings($clientId);

    if (isset($_POST['update_configuration'])) {

        $allowUnassignedIssuesFlag = $_POST['allow_unassigned_issues'];

        $parameters = array(array('field' => 'allow_unassigned_issues_flag', 'value' => $allowUnassignedIssuesFlag, 'type' => 'i'));

        Client::updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_YONGO, $parameters);

        $currentDate = Util::getServerCurrentDateTime();
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo General Settings', $currentDate);

        header('Location: /yongo/administration/general-configuration');
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update General Configuration';

    require_once __DIR__ . '/../../../Resources/views/administration/general_configuration/Edit.php';
