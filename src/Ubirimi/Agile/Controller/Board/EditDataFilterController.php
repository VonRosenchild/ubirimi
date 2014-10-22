<?php

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditDataFilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';

        $boardId = $request->get('id');
        $board = $this->getRepository('agile.board.board')->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $boardProjects = $this->getRepository('agile.board.board')->getProjects($boardId, 'array');
        $filter = $this->getRepository('yongo.issue.filter')->getById($board['filter_id']);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CHEETAH_NAME
            . ' / Board / Filter';

        return $this->render(__DIR__ . '/../../Resources/views/board/EditDataFilter.php', get_defined_vars());
    }
}
