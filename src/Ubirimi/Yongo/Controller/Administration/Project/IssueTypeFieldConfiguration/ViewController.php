<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\IssueTypeFieldConfiguration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $fieldConfigurations = FieldConfigurationScheme::getFieldConfigurations($project['issue_type_field_configuration_id']);
        $allFields = $this->getRepository(Field::class)->getByClient($session->get('client/id'));
        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Type Field Configuration';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/ViewIssueTypeFieldConfiguration.php', get_defined_vars());
    }
}
