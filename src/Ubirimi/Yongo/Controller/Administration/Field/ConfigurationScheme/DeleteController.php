<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($Id);

    FieldConfigurationScheme::deleteDataByFieldConfigurationSchemeId($Id);
    FieldConfigurationScheme::deleteById($Id);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Field Configuration Scheme ' . $fieldConfigurationScheme['name'], $currentDate);