<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $menuSelectedCategory = 'system';

        $linkTypes = IssueLinkType::getByClientId($session->get('client/id'));
        $issueLinkingFlag = $session->get('yongo/settings/issue_linking_flag');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Link Types';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/List.php', get_defined_vars());
    }
}
