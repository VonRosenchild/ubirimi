<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FindPageController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $spaceId = $_POST['space_id'];
        $pageNameKeyword = $_POST['page'];

        $pages = Entity::findBySpaceIdAndKeyword($clientId, $spaceId, $pageNameKeyword);

        return $this->render(__DIR__ . '/../../../Resources/views/page/Find.php', get_defined_vars());
    }
}