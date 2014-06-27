<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\AgileBoard;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\UbirimiController;

class EditSwimlaneController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';

        $boardId = $request->get('id');
        $board = AgileBoard::getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board / Swimlanes';

        return $this->render(__DIR__ . '/../../Resources/views/board/EditDataSwimlane.php', get_defined_vars());
    }
}
