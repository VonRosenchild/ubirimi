<?php

namespace Ubirimi\Yongo\Controller\Administration\Field;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Custom;

class EditMetadataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $customField = $this->getRepository(CustomField::class)->getById($Id);

        if ($customField['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $empty_label = false;
        $duplicate_name = false;
        $duplicate_label = false;

        if ($request->request->has('edit_custom_field')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));

            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $date = Util::getServerCurrentDateTime();

                CustomField::updateMetaDataById($Id, $name, $description, $date);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Custom Field ' . $name,
                    $date
                );

                return new RedirectResponse('/yongo/administration/custom-fields');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Custom Field';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/field/EditMetadata.php', get_defined_vars());
    }
}
