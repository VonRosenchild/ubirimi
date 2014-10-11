<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Custom;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\Configuration;
use Ubirimi\Yongo\Repository\Screen\Screen;

class EditScreenVisibilityController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldId = $request->get('id');
        $field = Field::getById($fieldId);

        if ($field['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $screens = Screen::getAll($session->get('client/id'));

        if ($request->request->has('edit_field_custom_screen')) {
            $currentDate = Util::getServerCurrentDateTime();

            while ($screen = $screens->fetch_array(MYSQLI_ASSOC)) {
                Screen::deleteDataByScreenIdAndFieldId($screen['id'], $fieldId);
            }

            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 13) == 'field_screen_') {
                    $data = str_replace('field_screen_', '', $key);
                    $values = explode('_', $data);
                    $fieldSelectedId = $values[0];
                    $screenSelectedId = $values[1];
                    Screen::addData($screenSelectedId, $fieldSelectedId, null, $currentDate);
                }
            }

            // make field visible in all the field configurations

            $fieldConfigurations = Configuration::getByClientId($session->get('client/id'));
            while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)) {
                Configuration::addCompleteData($fieldConfiguration['id'], $fieldId, 1, 0, '');
            }

            Log::add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Custom Field ' . $field['name'],
                $currentDate
            );

            if ($field['sys_field_type_id'] == Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CODE_ID) {
                return new RedirectResponse('/yongo/administration/custom-fields/define/' . $fieldId);
            } else {
                return new RedirectResponse('/yongo/administration/custom-fields');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Custome Field';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/custom/EditScreen.php', get_defined_vars());
    }
}