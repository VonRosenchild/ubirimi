<?php

namespace Ubirimi\Documentador\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FindPageController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $spaceId = $request->request->get('space_id');
        $pageNameKeyword = $request->request->get('page');

        $pages = $this->getRepository(Entity::class)->findBySpaceIdAndKeyword($clientId, $spaceId, $pageNameKeyword);

        return $this->render(__DIR__ . '/../../../Resources/views/page/Find.php', get_defined_vars());
    }
}