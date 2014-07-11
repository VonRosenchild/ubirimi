<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Repository\Log;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('field_configuration_id');
        $fieldId = $request->get('field_id');
        $visibleFlag = $request->get('visible_flag');
        $requiredFlag = $request->get('required_flag');

        $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);
        $data = FieldConfiguration::getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
        if (!$data)
            FieldConfiguration::addSimpleData($fieldConfigurationId, $fieldId);

        FieldConfiguration::updateData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag);

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Field Configuration ' . $fieldConfiguration['name'],
            $currentDate
        );

        return new RedirectResponse('/yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
    }
}
