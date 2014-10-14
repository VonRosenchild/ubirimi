<?php

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;

class ListFilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientSettings = $session->get('client/settings');
            $filters = Filter::getAllByUser($session->get('user/id'));
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
            $filters = Filter::getAllByClientId($clientId);
        }

        $loggedInUserId = $session->get('user/id');
        $menuSelectedCategory = 'filters';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Filters';

        return $this->render(__DIR__ . '/../Resources/views/filter/List.php', get_defined_vars());
    }
}