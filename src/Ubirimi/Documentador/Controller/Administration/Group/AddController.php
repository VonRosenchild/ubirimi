<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('new_group')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository('ubirimi.user.group')->getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name);
                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $description = Util::cleanRegularInputField($request->request->get('description'));
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('ubirimi.user.group')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name, $description, $currentDate);

                return new RedirectResponse('/documentador/administration/groups');
            }
        }

        $menuSelectedCategory = 'doc_users';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/Add.php', get_defined_vars());
    }
}