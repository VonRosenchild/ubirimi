<?php

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);
        $menuSelectedCategory = 'notebooks';
        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'));

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME
            . ' / My Notebooks';

        return $this->render(__DIR__ . '/../../Resources/views/Notebook/List.php', get_defined_vars());
    }
}
