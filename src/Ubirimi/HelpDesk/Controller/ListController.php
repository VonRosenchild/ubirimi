<?php

namespace Ubirimi\HelpDesk\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projects = $this->getRepository('ubirimi.general.client')->getProjects($session->get('client/id'), null, null, true);

        $menuSelectedCategory = 'help_desk';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Help Desks';

        return $this->render(__DIR__ . '/../Resources/views/List.php', get_defined_vars());
    }
}
