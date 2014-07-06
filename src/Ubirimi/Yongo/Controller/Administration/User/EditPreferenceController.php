<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $settings = Client::getYongoSettings($clientId);
    $menuSelectedCategory = 'user';

    if (isset($_POST['edit_settings'])) {
        $issuesPerPage = $_POST['issues_per_page'];
        $notifyOwnChanges = $_POST['notify_own_changes'];
        $parameters = array(array('field' => 'issues_per_page', 'value' => $issuesPerPage, 'type' => 'i'),
                            array('field' => 'notify_own_changes_flag', 'value' => $notifyOwnChanges, 'type' => 'i'));

        Client::updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_YONGO, $parameters);

        $currentDate = Util::getServerCurrentDateTime();
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Global User Preferences', $currentDate);

        header('Location: /yongo/administration/user-preference');
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update User Preferences';

    require_once __DIR__ . '/../../../Resources/views/administration/user/EditPreference.php';