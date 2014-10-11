<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\LinkType;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $linkTypeId = $request->get('id');

        $issues = LinkType::getByLinkTypeId($linkTypeId);
        $linkTypes = LinkType::getByClientId($session->get('client/id'));

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/DeleteConfirm.php', get_defined_vars());
    }
}
