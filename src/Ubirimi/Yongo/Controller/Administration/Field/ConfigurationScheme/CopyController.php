<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\ConfigurationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;


class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationSchemeId = $request->get('id');
        $fieldConfigurationScheme = FieldConfigurationScheme::getMetaDataById($fieldConfigurationSchemeId);

        if ($fieldConfigurationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_field_configuration_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateFieldConfigurationScheme = FieldConfigurationScheme::getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateFieldConfigurationScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();

                $copiedFieldConfigurationScheme = new FieldConfigurationScheme(
                    $session->get('client/id'),
                    $name,
                    $description
                );

                $copiedFieldConfigurationSchemeId = $copiedFieldConfigurationScheme->save($currentDate);

                $fieldConfigurationSchemeData = FieldConfigurationScheme::getDataByFieldConfigurationSchemeId(
                    $fieldConfigurationSchemeId
                );

                while ($fieldConfigurationSchemeData && $data = $fieldConfigurationSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedFieldConfigurationScheme->addData(
                        $copiedFieldConfigurationSchemeId,
                        $data['field_configuration_id'],
                        $data['issue_type_id'],
                        $currentDate
                    );
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Field Configuration Scheme ' . $fieldConfigurationScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/field-configurations/schemes');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Field Configuration Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Copy.php', get_defined_vars());
    }
}
