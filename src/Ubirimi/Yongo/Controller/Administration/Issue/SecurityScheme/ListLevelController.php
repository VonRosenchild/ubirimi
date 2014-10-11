<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SecurityScheme;
use Ubirimi\SystemProduct;

class ListLevelController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeId = $request->get('id');
        $issueSecurityScheme = SecurityScheme::getMetaDataById($issueSecuritySchemeId);

        if ($issueSecurityScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'issue';

        $issueSecuritySchemeLevels = SecurityScheme::getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Security Scheme Levels';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/ListLevel.php', get_defined_vars());
    }
}
