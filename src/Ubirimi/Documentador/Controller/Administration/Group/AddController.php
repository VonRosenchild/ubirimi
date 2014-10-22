<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

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

        if (isset($_POST['new_group'])) {
            $name = Util::cleanRegularInputField($_POST['name']);
            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository('ubirimi.user.group')->getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name);
                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $description = Util::cleanRegularInputField($_POST['description']);
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('ubirimi.user.group')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $name, $description, $currentDate);

                header('Location: /documentador/administration/groups');
            }
        }

        $menuSelectedCategory = 'doc_users';

        require_once __DIR__ . '/../../../Resources/views/administration/group/Add.php';
    }
}