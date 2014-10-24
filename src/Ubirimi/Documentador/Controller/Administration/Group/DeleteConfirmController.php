<?php

namespace Ubirimi\Documentador\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $groupId = $request->get('id');
        $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($groupId);

        return $this->render(__DIR__ . '/../../Resources/views/administration/group/DeleteConfirm.php', get_defined_vars());

    }
}