<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Scheme;
use Ubirimi\Repository\Log;

class EditMetadataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->get('id');
        $permissionScheme = Scheme::getMetaDataById($permissionSchemeId);

        if ($permissionScheme['client_id'] != $session->get('client/id')) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $emptyName = false;
        if ($request->request->has('edit_permission_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                Scheme::updateMetaDataById($permissionSchemeId, $name, $description, $currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Permission Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/permission-schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Permission Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/EditMetadata.php', get_defined_vars());
    }
}
