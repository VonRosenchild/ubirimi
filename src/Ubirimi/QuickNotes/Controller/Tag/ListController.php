<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\SystemProduct;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);
        $menuSelectedCategory = 'tags';
        $tags = Tag::getByUserId($session->get('user/id'));

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME
            . ' / tags';

        return $this->render(__DIR__ . '/../../Resources/views/Tag/List.php', get_defined_vars());
    }
}
