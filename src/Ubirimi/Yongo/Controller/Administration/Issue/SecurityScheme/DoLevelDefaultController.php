<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SecurityScheme;

class DoLevelDefaultController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $securityLevelId = $request->get('id');
        $securityLevel = SecurityScheme::getLevelById($securityLevelId);
        $securityScheme = SecurityScheme::getMetaDataById($securityLevel['issue_security_scheme_id']);
        SecurityScheme::makeAllLevelsNotDefault($securityScheme['id']);
        SecurityScheme::setLevelDefault($securityLevelId);

        return new RedirectResponse('/yongo/administration/issue-security-scheme-levels/' . $securityScheme['id']);
    }
}
