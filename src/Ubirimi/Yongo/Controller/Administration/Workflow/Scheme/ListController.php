<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Scheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Scheme;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $workflowSchemes = Scheme::getMetaDataByClientId($session->get('client/id'));
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Schemes';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/scheme/List.php', get_defined_vars());
    }
}
