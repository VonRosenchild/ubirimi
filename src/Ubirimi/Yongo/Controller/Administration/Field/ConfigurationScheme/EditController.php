<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\ConfigurationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $fieldConfigurationSchemeId = $request->get('id');
        $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($fieldConfigurationSchemeId);

        if ($fieldConfigurationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $fieldConfigurationSchemeData = FieldConfigurationScheme::getDataByFieldConfigurationSchemeId(
            $fieldConfigurationSchemeId
        );

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Edit.php', get_defined_vars());
    }
}
