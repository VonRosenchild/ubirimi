<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\IssueTypeScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $issueTypeSchemes = IssueTypeScheme::getByClientId($session->get('client/id'), 'workflow');
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Issue Type Schemes';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/workflow/issue_type_scheme/List.php', get_defined_vars());
    }
}
