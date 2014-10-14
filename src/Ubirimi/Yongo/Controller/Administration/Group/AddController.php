<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;


class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('new_group')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            if (empty($name)) {
                $emptyName = true;
            }

            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository('ubirimi.user.group')->getByNameAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO, $name);
                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $description = Util::cleanRegularInputField($_POST['description']);
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository('ubirimi.user.group')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $name,
                    $description,
                    $currentDate
                );

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Group ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/groups');
            }
        }

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Group';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/Add.php', get_defined_vars());
    }
}
