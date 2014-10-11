<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\IssueTypeScreenScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;

class SelectSchemeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = Project::getById($projectId);
        
        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $issueTypeScreenSchemes = TypeScreenScheme::getByClientId($session->get('client/id'));

        $menuSelectedCategory = 'project';

        if ($request->request->has('associate')) {

            $issueTypeScreenSchemeId = $_POST['issue_type_screen_scheme'];
            Project::updateIssueTypeScreenScheme($projectId, $issueTypeScreenSchemeId);

            return new RedirectResponse('/yongo/administration/project/screens/' . $projectId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Issue Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/SelectIssueTypeScreenScheme.php', get_defined_vars());
    }
}
