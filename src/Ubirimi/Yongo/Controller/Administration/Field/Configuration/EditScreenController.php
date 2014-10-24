<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Screen\Screen;

class EditScreenVisibilityController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('field_configuration_id');
        $fieldId = $request->get('id');

        $fieldConfiguration = FieldConfiguration::getMetaDataById($fieldConfigurationId);

        $field = $this->getRepository(Field::class)->getById($fieldId);
        $screens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));

        if ($request->request->has('edit_field_configuration_screen')) {
            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository(Screen::class)->deleteDataByFieldId($fieldId);
            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 13) == 'field_screen_') {
                    $data = str_replace('field_screen_', '', $key);
                    $values = explode('_', $data);
                    $fieldSelectedId = $values[0];
                    $screenSelectedId = $values[1];
                    $this->getRepository(Screen::class)->addData($screenSelectedId, $fieldSelectedId, null, $currentDate);
                }
            }

            return new RedirectResponse('/yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration Screen';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/EditScreen.php', get_defined_vars());
    }
}
