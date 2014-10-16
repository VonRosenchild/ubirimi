<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

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
        $Id = $request->get('id');
        $group = $this->getRepository('ubirimi.user.group')->getMetadataById($Id);

        if ($group['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $name = $group['name'];
        $description = $group['description'];

        $emptyName = false;
        $duplicateName = false;

        if (isset($_POST['update_group'])) {
            $name = Util::cleanRegularInputField($_POST['name']);
            $description = Util::cleanRegularInputField($_POST['description']);

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

                header('Location: /documentador/administration/groups');
            }
        }
        $menuSelectedCategory = 'doc_users';

        require_once __DIR__ . '/../../../Resources/views/administration/group/Edit.php';
    }
}