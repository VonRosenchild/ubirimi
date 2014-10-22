<?php

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditPermissionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_spaces';

        $spaceId = $request->get('id');
        $space = $this->getRepository('documentador.space.space')->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if (isset($_POST['update_configuration'])) {

            // deal with the groups
            // delete all the permissions for all groups
            $this->getRepository('documentador.space.space')->removePermissionsForAllGroups($spaceId);
            $groupPermissions = array();
            foreach ($_POST as $key => $value) {

                if (substr($key, 0, 26) == 'space_group_all_view_flag_') {
                    $groupId = str_replace('space_group_all_view_flag_', '', $key);
                    $groupPermissions[$groupId]['all_view_flag'] = 1;
                }
                if (substr($key, 0, 29) == 'space_group_space_admin_flag_') {
                    $groupId = str_replace('space_group_space_admin_flag_', '', $key);
                    $groupPermissions[$groupId]['space_admin_flag'] = 1;
                }
            }

            $this->getRepository('documentador.space.space')->updateGroupPermissions($spaceId, $groupPermissions);

            // deal with the users
            // delete all the permissions for all users
            $this->getRepository('documentador.space.space')->removePermissionsForAllUsers($spaceId);
            $usersPermissions = array();
            foreach ($_POST as $key => $value) {

                if (substr($key, 0, 25) == 'space_user_all_view_flag_') {
                    $userId = str_replace('space_user_all_view_flag_', '', $key);
                    $usersPermissions[$userId]['all_view_flag'] = 1;
                }
                if (substr($key, 0, 28) == 'space_user_space_admin_flag_') {
                    $userId = str_replace('space_user_space_admin_flag_', '', $key);
                    $usersPermissions[$userId]['space_admin_flag'] = 1;
                }
            }

            $this->getRepository('documentador.space.space')->updateUserPermissions($spaceId, $usersPermissions);

            // deal with anonymous access
            $anonymous_all_view_flag = isset($_POST['anonymous_all_view_flag']) ? $_POST['anonymous_all_view_flag'] : 0;

            $parameters = array(array('field' => 'all_view_flag', 'value' => $anonymous_all_view_flag, 'type' => 'i'));

            $this->getRepository('documentador.space.space')->updatePermissionsAnonymous($spaceId, $parameters);

            return new RedirectResponse('/documentador/administration/space-tools/permissions/' . $spaceId);
        }

        $users = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
        $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $anonymousAccessSettings = $this->getRepository('documentador.space.space')->getAnonymousAccessSettings($spaceId);

        require_once __DIR__ . '/../../../Resources/views/administration/spacetools/EditPermission.php';
    }
}