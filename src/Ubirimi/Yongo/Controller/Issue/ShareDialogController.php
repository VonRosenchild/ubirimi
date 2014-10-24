<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ShareDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = UbirimiContainer::get()['session']->get('client/id');

        $issueId = $request->get('id');

        $users = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId, 0);
        $subdomain = Util::getSubdomain();

        return $this->render(__DIR__ . '/../../Resources/views/issue/ShareDialog.php', get_defined_vars());
    }
}