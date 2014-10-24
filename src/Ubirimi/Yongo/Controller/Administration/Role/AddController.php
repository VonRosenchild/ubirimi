<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('new_role')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $role = $this->getRepository('yongo.permission.role')->getByName($session->get('client/id'), $name);
            if ($role)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.permission.role')->gadd($session->get('client/id'), $name, $description, $date);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Project Role ' . $name,
                    $date
                );

                return new RedirectResponse('/yongo/administration/roles');
            }
        }

        $menuSelectedCategory = 'user';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Role';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/Add.php', get_defined_vars());
    }
}
