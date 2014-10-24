<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

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
                $groupAlreadyExists = $this->getRepository(UbirimiGroup::class)->getByNameAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO, $name);
                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $description = Util::cleanRegularInputField($request->request->get('description'));
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(UbirimiGroup::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $name,
                    $description,
                    $currentDate
                );

                $this->getRepository(UbirimiLog::class)->add(
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
