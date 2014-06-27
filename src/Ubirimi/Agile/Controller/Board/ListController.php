<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $boards = AgileBoard::getByClientId($session->get('client/id'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Boards';

        return $this->render(__DIR__ . '/../../Resources/views/board/List.php', get_defined_vars());
    }
}
