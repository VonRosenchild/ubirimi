<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Category;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        }

        $projects = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Projects';
        $projectCategories = Category::getAll($clientId);
        $includeCheckbox = false;

        return $this->render(__DIR__ . '/../../Resources/views/project/List.php', get_defined_vars());
    }
}
