<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewActivityStreamController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $projectId = $request->request->get('id');
        $loggedInUserId = $session->get('user/id');

        if (Util::checkUserIsLoggedIn()) {
            $hasBrowsingPermission = Project::userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $loggedInUserId);
        } else {
            $loggedInUserId = null;
            $httpHOST = Util::getHttpHost();
            $hasBrowsingPermission = Project::userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS);
        }

        if ($hasBrowsingPermission) {
            if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_HELP_DESK) {
                $historyList = Util::getProjectHistory(array($projectId), 1);
            } else {
                $historyList = Util::getProjectHistory(array($projectId), 0);
            }

            $date = null;
            $issueId = null;
        }

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewActivityStream.php', get_defined_vars());
    }
}
