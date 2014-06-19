<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'system';

    $settings = Client::getYongoSettings($clientId);

    if (isset($_POST['update_configuration'])) {

        $allowAttachmentsFlag = $_POST['allow_attachments_flag'];

        $parameters = array(array('field' => 'allow_attachments_flag', 'value' => $allowAttachmentsFlag, 'type' => 'i'));

        Client::updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_YONGO, $parameters);

        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Attachment Settings', $currentDate);

        header('Location: /yongo/administration/attachment-configuration');
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Attachment Configuration';

    require_once __DIR__ . '/../../../Resources/views/administration/attachment/edit_configuration.php';