<?php

namespace Ubirimi\Yongo\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

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
            $projects = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjects($session->get('client/id'), 'array');
            $last5Projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getLast5ByClientId($session->get('client/id'));
            $countProjects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getCount($session->get('client/id'));
        } else if ($hasYongoAdministerProjectsPermission) {
            $projects = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission(
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
