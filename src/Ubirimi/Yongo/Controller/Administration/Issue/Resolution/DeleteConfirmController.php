<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Resolution;

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

        $resolutions = IssueSettings::getAllIssueSettings('resolution', $session->get('client/id'));

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/resolution/DeleteConfirm.php', get_defined_vars());
    }
}
