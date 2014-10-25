<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
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
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Dashboard';
        }

        $type = $request->get('type');

        $menuSelectedCategory = 'documentator';

        if ($type == 'spaces') {
            if (Util::checkUserIsLoggedIn()) {
                $spaces = $this->getRepository(Space::class)->getByClientId($session->get('client/id'), 1);
            } else {
                $spaces = $this->getRepository(Space::class)->getByClientIdAndAnonymous($session->get('client/id'));
            }
        } else if ($type == 'pages') {
            $pages = $this->getRepository(Entity::class)->getFavouritePagesByClientIdAndUserId($session->get('client/id'), $loggedInUserId);
        }

        return $this->render(__DIR__ . '/../Resources/views/Dashboard.php', get_defined_vars());
    }
}