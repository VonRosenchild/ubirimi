<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

    Util::checkUserIsLoggedInAndRedirect();

    $fieldConfigurationId = $_GET['field_configuration_id'];
    $fieldId = $_GET['field_id'];
    $visibleFlag = isset($_GET['visible_flag']) ? $_GET['visible_flag'] : null;
    $requiredFlag = isset($_GET['required_flag']) ? $_GET['required_flag'] : null;

    $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);
    $data = FieldConfiguration::getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
    if (!$data)
        FieldConfiguration::addSimpleData($fieldConfigurationId, $fieldId);

    FieldConfiguration::updateData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Field Configuration ' . $fieldConfiguration['name'], $currentDate);

    header('Location: /yongo/administration/field-configuration/edit/' . $fieldConfigurationId);