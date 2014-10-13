<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Documentador\Entity;
use Ubirimi\Repository\Documentador\Space;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DashboardController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $loggedInUserId = null;

        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $sectionPageTitle = $session->get('client/settings/title_name')
                . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME
                . ' / Dashboard';

        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Dashboard';
        }

        $type = $request->get('type');

        $menuSelectedCategory = 'documentator';

        if ($type == 'spaces') {
            if (Util::checkUserIsLoggedIn())
                $spaces = Space::getByClientId($session->get('client/id'), 1);
            else
                $spaces = Space::getByClientIdAndAnonymous($session->get('client/id'));
        } else if ($type == 'pages') {
            $pages = Entity::getFavouritePagesByClientIdAndUserId($session->get('client/id'), $loggedInUserId);
        }

        return $this->render(__DIR__ . '/../Resources/views/Dashboard.php', get_defined_vars());
    }
}
