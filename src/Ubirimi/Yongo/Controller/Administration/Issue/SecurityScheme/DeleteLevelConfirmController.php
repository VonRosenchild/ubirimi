<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteLevelConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeLevelId = $request->get('id');
        $issueSecuritySchemeLevel = IssueSecurityScheme::getLevelById($issueSecuritySchemeLevelId);
        $issueSecuritySchemeId = $issueSecuritySchemeLevel['issue_security_scheme_id'];
        $allLevels = IssueSecurityScheme::getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);

        $issues = Issue::getByParameters(
            array(
                'client_id' => $session->get('client/id'),
                'security_scheme_level' => $issueSecuritySchemeLevelId
            )
        );

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/DeleteLevelConfirm.php', get_defined_vars());
    }
}
