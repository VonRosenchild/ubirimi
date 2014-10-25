<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('field_configuration_id');
        $fieldId = $request->get('field_id');
        $visibleFlag = $request->get('visible_flag');
        $requiredFlag = $request->get('required_flag');

        $fieldConfiguration = $this->getRepository(FieldConfiguration::class)->getMetaDataById($fieldConfigurationId);
        $data = $this->getRepository(FieldConfiguration::class)->getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
        if (!$data)
            $this->getRepository(FieldConfiguration::class)->addSimpleData($fieldConfigurationId, $fieldId);

        $this->getRepository(FieldConfiguration::class)->updateData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Field Configuration ' . $fieldConfiguration['name'],
            $currentDate
        );

        return new RedirectResponse('/yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
    }
}
