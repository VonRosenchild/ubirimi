<?php

namespace Ubirimi\Documentador\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UserSpacesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');

            $spaces = $this->getRepository(Space::class)->getByClientId($clientId);
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Spaces';
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
            $spaces = $this->getRepository(Space::class)->getByClientIdAndAnonymous($clientId);
            $loggedInUserId = null;
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Spaces';
        }

        $menuSelectedCategory = 'documentator';

        return $this->render(__DIR__ . '/../../Resources/views/UserSpaces.php', get_defined_vars());
    }
}