<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\Yongo\Repository\Permission\Scheme;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->get('id');
        $permissionScheme = $this->getRepository('yongo.permission.scheme')->getMetaDataById($permissionSchemeId);

        if ($permissionScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_permission_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $duplicatePermissionScheme = $this->getRepository('yongo.permission.scheme')->getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicatePermissionScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedPermissionScheme = new Scheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedPermissionSchemeId = $copiedPermissionScheme->save($currentDate);

                $permissionSchemeData = $this->getRepository('yongo.permission.scheme')->getDataByPermissionSchemeId($permissionSchemeId);

                while ($permissionSchemeData && $data = $permissionSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedPermissionScheme->addDataRaw(
                        $copiedPermissionSchemeId,
                        $data['sys_permission_id'],
                        $data['permission_role_id'],
                        $data['group_id'],
                        $data['user_id'],
                        $data['current_assignee'],
                        $data['reporter'],
                        $data['project_lead'],
                        $currentDate
                    );
                }

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Permission Scheme ' . $permissionScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/permission-schemes');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Permission Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/Copy.php', get_defined_vars());
    }
}
