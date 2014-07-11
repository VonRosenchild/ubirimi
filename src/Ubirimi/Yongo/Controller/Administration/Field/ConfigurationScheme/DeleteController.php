<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\ConfigurationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($Id);

        FieldConfigurationScheme::deleteDataByFieldConfigurationSchemeId($Id);
        FieldConfigurationScheme::deleteById($Id);

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Field Configuration Scheme ' . $fieldConfigurationScheme['name'],
            $currentDate
        );

        return new Response('');
    }
}
