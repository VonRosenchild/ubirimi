<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Custom;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

class EditValueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $valueId = $request->get('id');

        $fieldValue = $this->getRepository(Field::class)->getDataById($valueId);
        $customFieldId = $fieldValue['field_id'];
        $field = $this->getRepository(Field::class)->getById($customFieldId);

        $emptyValue = false;
        $duplicateValue = false;

        if ($request->request->has('edit_custom_field_value')) {
            $value = Util::cleanRegularInputField($request->request->get('value'));

            if (empty($value)) {
                $emptyValue = true;
            }

            // check for duplication
            $valueDataExists = $this->getRepository(Field::class)->getDataByFieldIdAndValue($customFieldId, $value, $valueId);

            if ($valueDataExists)
                $duplicateValue = true;

            if (!$duplicateValue && !$emptyValue) {
                $currentDate = Util::getServerCurrentDateTime();
                Field::updateDataById($valueId, $value, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Custom Field Value ' . $value,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/custom-fields/define/' . $customFieldId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Custom Field Value';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/custom/EditValue.php', get_defined_vars());
    }
}