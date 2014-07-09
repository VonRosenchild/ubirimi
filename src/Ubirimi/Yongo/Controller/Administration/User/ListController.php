<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Group\Group;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterGroupId = $request->get('group_id');

        if ($filterGroupId) {
            $group = Group::getMetadataById($filterGroupId);
            if ($group['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $users = Client::getUsers($session->get('client/id'), $filterGroupId, null, 1);

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';
        $allGroups = Group::getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/List.php', get_defined_vars());
    }
}
