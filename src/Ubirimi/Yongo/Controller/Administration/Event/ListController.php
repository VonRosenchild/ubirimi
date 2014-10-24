<?php

namespace Ubirimi\Yongo\Controller\Administration\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $events = $this->getRepository(IssueEvent::class)->getByClient($session->get('client/id'));
        $menuSelectedCategory = 'system';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Events';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/event/list.php', get_defined_vars());
    }
}