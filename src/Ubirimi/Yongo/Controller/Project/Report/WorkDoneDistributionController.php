<?php

namespace Ubirimi\Yongo\Controller\Project\Report;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class WorkDoneDistributionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $loggedInUserId = null;
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        }

        $projectId = $request->get('id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        $workData = $this->getRepository('yongo.project.project')->getWorkDoneDistributition($projectId, $dateFrom, $dateTo, 'array');
        $workDataPrepared = array();

        if ($workData) {
            foreach ($workData as $data) {
                $workDataPrepared[$data['first_name'] . ' ' . $data['last_name']][$data['type_name']] = $data['total'];
            }
        }

        $issueTypes = $this->getRepository('yongo.project.project')->getIssueTypes($projectId, true, 'array');
        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('filter')) {
            $dateFrom = $request->request->get('filter_from_date');
            $dateTo = $request->request->get('filter_to_date');

            return new RedirectResponse('/yongo/project/reports/' . $projectId . '/work-done-distribution/' . $dateFrom . '/' . $dateTo);
        }

        $hasGlobalAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);

        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'] . ' / Reports / Work Done Distribution';
        $menuSelectedCategory = 'project';
        $menuProjectCategory = 'reports';
        
        return $this->render(__DIR__ . '/../../../Resources/views/project/report/ViewWorkDoneDistribution.php', get_defined_vars());
    }
}
