<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

class DeleteLevelDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeLevelDataId = $request->request->get('id');
        $issueSecuritySchemeLevelData = IssueSecurityScheme::getLevelDataById($issueSecuritySchemeLevelDataId);
        $issueSecuritySchemeLevelId = $issueSecuritySchemeLevelData['issue_security_scheme_level_id'];
        $issueSecuritySchemeLevel = IssueSecurityScheme::getLevelById($issueSecuritySchemeLevelId);

        IssueSecurityScheme::deleteLevelDataById($issueSecuritySchemeLevelDataId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Issue Security Scheme Level ' . $issueSecuritySchemeLevel['name'],
            $date
        );

        return new Response('');
    }
}
