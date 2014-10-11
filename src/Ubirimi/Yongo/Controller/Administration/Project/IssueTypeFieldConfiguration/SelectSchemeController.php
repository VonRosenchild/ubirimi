<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\IssueTypeFieldConfiguration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Field\ConfigurationScheme;

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
        $fieldConfigurationSchemes = ConfigurationScheme::getByClient($session->get('client/id'));

        $menuSelectedCategory = 'project';

        if ($request->request->has('associate')) {
            $issueTypeFieldSchemeId = $request->request->get('issue_type_field_scheme');
            Project::updateFieldConfigurationScheme($projectId, $issueTypeFieldSchemeId);

            return new RedirectResponse('/yongo/administration/project/fields/' . $projectId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Issue Type Field Configuration';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/SelectIssueTypeFieldScheme.php', get_defined_vars());
    }
}
