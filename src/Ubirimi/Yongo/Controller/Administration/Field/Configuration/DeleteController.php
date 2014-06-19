<?php

    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $fieldConfiguration = FieldConfiguration::getMetaDataById($Id);

    FieldConfiguration::deleteDataByFieldConfigurationId($Id);
    FieldConfiguration::deleteById($Id);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Field Configuration ' . $fieldConfiguration['name'], $currentDate);