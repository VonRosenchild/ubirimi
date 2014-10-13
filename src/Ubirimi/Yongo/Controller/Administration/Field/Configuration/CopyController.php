<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Configuration;
use Ubirimi\Repository\Log;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('id');
        $fieldConfiguration = Configuration::getMetaDataById($fieldConfigurationId);

        if ($fieldConfiguration['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_field_configuration')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateFieldConfiguration = Configuration::getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateFieldConfiguration)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedFieldConfiguration = new Configuration($session->get('client/id'), $name, $description);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedFieldConfigurationId = $copiedFieldConfiguration->save($currentDate);

                $fieldConfigurationData = Configuration::getDataByConfigurationId($fieldConfigurationId);

                while ($fieldConfigurationData && $data = $fieldConfigurationData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedFieldConfiguration->addCompleteData(
                        $copiedFieldConfigurationId,
                        $data['field_id'],
                        $data['visible_flag'],
                        $data['required_flag'],
                        $data['field_description']
                    );
                }

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Field Configuration ' . $fieldConfiguration['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/field-configurations');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Field Configuration';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/Copy.php', get_defined_vars());
    }
}
