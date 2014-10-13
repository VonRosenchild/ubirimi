<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $projectId = $request->get('id');

        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $project = $this->getRepository('yongo.project.project')->getById($projectId);
            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'];
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

            $project = $this->getRepository('yongo.project.project')->getById($projectId);
            $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'];
        }

        $hasBrowsingPermission = $this->getRepository('yongo.project.project')->userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $loggedInUserId);
        if ($project['client_id'] != $clientId || !$hasBrowsingPermission) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_project_id', $projectId);
        $menuSelectedCategory = 'project';

        $hasGlobalAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);
        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $menuProjectCategory = 'summary';

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewSummary.php', get_defined_vars());
    }
}
