<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\CustomField;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $customField = CustomField::getById($Id);

    CustomField::deleteById($Id);

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Custom Field ' . $customField['name'], $date);