<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class ListPagesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
            $loggedInUserId = null;
        }

        $spaceId = $request->get('space_id');
        $space = $this->getRepository(Space::class)->getById($spaceId);

        $menuSelectedCategory = 'documentator';
        $space = $this->getRepository(Space::class)->getById($spaceId);

        if ($space['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $spaceHasAnonymousAccess = $this->getRepository(Space::class)->hasAnonymousAccess($spaceId);
        $pages = $this->getRepository(Entity::class)->getAllBySpaceId($spaceId, 0);
        $homePage = $this->getRepository(Entity::class)->getById($space['home_entity_id']);

        if ($homePage['in_trash_flag']) {
            $homePage = null;
        }

        return $this->render(__DIR__ . '/../Resources/views/ListPages.php', get_defined_vars());
    }
}
