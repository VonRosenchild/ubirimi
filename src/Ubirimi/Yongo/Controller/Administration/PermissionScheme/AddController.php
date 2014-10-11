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

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        if ($request->request->has('add_permission_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $permissionScheme = new Scheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $permissionSchemeId = $permissionScheme->save($currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Permission Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/permission-schemes');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Permission Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/Add.php', get_defined_vars());
    }
}
