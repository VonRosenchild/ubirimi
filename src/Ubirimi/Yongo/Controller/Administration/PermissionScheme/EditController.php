<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $Id = $request->get('id');
        $backLink = $request->get('back');
        $projectId = $request->get('project_id');

        $permissionScheme = $this->getRepository(PermissionScheme::class)->getMetaDataById($Id);

        if ($permissionScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($projectId) {
            $project = $this->getRepository(YongoProject::class)->getById($projectId);
            if ($project['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $permissionCategories = Permission::getCategories();
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Permission Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/Edit.php', get_defined_vars());
    }
}
