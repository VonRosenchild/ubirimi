<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

class DeleteLevelConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeLevelId = $request->get('id');
        $issueSecuritySchemeLevel = $this->getRepository(IssueSecurityScheme::class)->getLevelById($issueSecuritySchemeLevelId);
        $issueSecuritySchemeId = $issueSecuritySchemeLevel['issue_security_scheme_id'];
        $allLevels = $this->getRepository(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);

        $issues = $this->getRepository(Issue::class)->getByParameters(
            array(
                'client_id' => $session->get('client/id'),
                'security_scheme_level' => $issueSecuritySchemeLevelId
            )
        );

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/DeleteLevelConfirm.php', get_defined_vars());
    }
}
