<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filters = array();
        if ($request->request->has('username_filter')) {
            $filters['username'] = $request->request->get('username_filter');
        }

        if ($request->request->has('fullname_filter')) {
            $filters['fullname'] = $request->request->get('fullname_filter');
        }

        if ($request->request->has('group_filter')) {
            $filters['group'] = $request->request->get('group_filter');
        }

        $users = $this->getRepository('ubirimi.general.client')->getUsersByClientIdAndProductIdAndFilters(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $filters
        );

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/_list_user.php', get_defined_vars());
    }
}
