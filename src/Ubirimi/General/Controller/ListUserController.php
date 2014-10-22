<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $session->set('selected_product_id', -1);
        $filterGroupId = $request->get('group_id');

        $users = UbirimiContainer::get()['repository']->get('ubirimi.general.client')->getUsers($clientId, $filterGroupId, null, 1);
        $menuSelectedCategory = 'general_user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Users';

        return $this->render(__DIR__ . '/../Resources/views/ListUser.php', get_defined_vars());
    }
}