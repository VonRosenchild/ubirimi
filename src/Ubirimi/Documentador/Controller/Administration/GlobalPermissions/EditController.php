<?php

namespace Ubirimi\Documentador\Controller\Administration\GlobalPermissions;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_users';

        $globalsPermissions = $this->getRepository('yongo.permission.globalPermission')->getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        if ($request->request->has('update_configuration')) {

            $anonymous_use_flag = $request->request->get('anonymous_use_flag');
            $anonymous_view_user_profile_flag = $request->request->get('anonymous_view_user_profile_flag');

            $parameters = array(array('field' => 'anonymous_use_flag', 'value' => $anonymous_use_flag, 'type' => 'i'),
                array('field' => 'anonymous_view_user_profile_flag', 'value' => $anonymous_view_user_profile_flag, 'type' => 'i'));

            $this->getRepository('ubirimi.general.client')->updateProductSettings($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $parameters);

            // deal with permissions added to groups

            // delete first all the permissions related to groups
            while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository('yongo.permission.globalPermission')->deleteByPermissionId($clientId, $globalsPermission['id'], 'group');
            }

            $date = Util::getServerCurrentDateTime();

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 5) == 'group') {
                    $data = explode("_", $key);
                    $globalsPermissionId = $data[1];
                    $groupId = $data[2];

                    $this->getRepository('yongo.permission.globalPermission')->addDataForGroupId($clientId, $globalsPermissionId, $groupId, $date);
                }
            }

            // deal with permissions added to users

            // delete first all the permissions related to individual users
            while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository('yongo.permission.globalPermission')->deleteByPermissionId($clientId, $globalsPermission['id'], 'user');
            }

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 4) == 'user') {
                    $data = explode("_", $key);
                    $globalsPermissionId = $data[1];
                    $userId = $data[2];

                    $this->getRepository('yongo.permission.globalPermission')->addDataForUserId($clientId, $globalsPermissionId, $userId);
                }
            }

            return new RedirectResponse('/documentador/administration/global-permissions');
        }
        $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
        $session->set('documentator/settings', $documentatorSettings);

        $users = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
        $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/globalpermissions/Edit.php', get_defined_vars());
    }
}