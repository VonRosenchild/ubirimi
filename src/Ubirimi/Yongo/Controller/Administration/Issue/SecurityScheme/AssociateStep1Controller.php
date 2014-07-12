<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Project\Project;

class AssociateStep1Controller extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $projectId = $request->get('id');
        $project = Project::getById($projectId);
        $menuSelectedCategory = 'project';
        $issueSecuritySchemes = IssueSecurityScheme::getByClientId($session->get('client/id'));

        if ($request->request->has('cancel')) {
            return new RedirectResponse('/yongo/administration/project/issue-security/' . $projectId);
        } elseif ($request->request->has('next')) {
            $schemeId = $request->request->get('scheme');
            return new RedirectResponse('/yongo/administration/project/associate-issue-security-level/' . $projectId . '/' . $schemeId);
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Associate Issue Security';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AssociateStep1.php', get_defined_vars());
    }
}
