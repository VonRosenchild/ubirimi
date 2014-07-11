<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueType;

class ListSubtaskController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $types = IssueType::getAllSubTasks($session->get('client/id'));

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Sub-Task Issue Types';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/type/ListSubtask.php', get_defined_vars());
    }
}
