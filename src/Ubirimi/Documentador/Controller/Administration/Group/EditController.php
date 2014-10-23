<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $Id = $request->get('id');
        $group = $this->getRepository('ubirimi.user.group')->getMetadataById($Id);

        if ($group['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $name = $group['name'];
        $description = $group['description'];

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('update_group')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository('ubirimi.user.group')->getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO, mb_strtolower($name), $Id);

                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('ubirimi.user.group')->updateById($Id, $name, $description, $currentDate);

                return new RedirectResponse('/documentador/administration/groups');
            }
        }
        $menuSelectedCategory = 'doc_users';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/Edit.php', get_defined_vars());
    }
}