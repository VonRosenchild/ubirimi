<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class FilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filters = array();

        if ($request->request->has('name_filter')) {
            $filters['name'] = $request->request->get('name_filter');
        }

        $groups = $this->getRepository(UbirimiClient::class)->getGroupsByClientIdAndProductIdAndFilters(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $filters
        );

        $menuSelectedCategory = 'group';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Groups';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/_list_group.php', get_defined_vars());
    }
}