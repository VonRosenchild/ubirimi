<?php

namespace Ubirimi\Frontend\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SignoutController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientBaseURL = $session->get('client/base_url');
        $date = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add($session->get('client/id'), SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $session->get('client/id'), 'LOG OUT', $date);

        $session->invalidate();

        return new RedirectResponse($clientBaseURL);
    }
}
