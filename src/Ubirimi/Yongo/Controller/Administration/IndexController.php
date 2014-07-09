<?php

namespace Ubirimi\Yongo\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $hasYongoGlobalAdministrationPermission = $session->get('user/yongo/is_global_administrator');
        $hasYongoGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
        $hasYongoAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

        $menuSelectedCategory = 'administration';

        if ($hasYongoGlobalAdministrationPermission && $hasYongoGlobalSystemAdministrationPermission) {
            $projects = Client::getProjects($session->get('client/id'), 'array');
            $last5Projects = Project::getLast5ByClientId($session->get('client/id'));
            $countProjects = Project::getCount($session->get('client/id'));
        } else if ($hasYongoAdministerProjectsPermission) {
            $projects = Client::getProjectsByPermission(
                $session->get('client/id'),
                $session->get('user/id'),
                Permission::PERM_ADMINISTER_PROJECTS,
                'array'
            );
            $countProjects = count($projects);
            $last5Projects = null;
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Administration';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Index.php', get_defined_vars());
    }
}
