<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Priority;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');

        $priorities = IssueSettings::getAllIssueSettings('priority', $session->get('client/id'));

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/priority/DeleteConfirm.php', get_defined_vars());
    }
}
