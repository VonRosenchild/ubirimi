<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\Log;

class SignOutController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $date = Util::getServerCurrentDateTime();

        Log::add(
            $session->has('client/id'),
            SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS,
            $session->get('user/id'),
            'LOG OUT',
            $date
        );

        // Unset all of the session variables.
        $session->invalidate();

        // also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.

        session_destroy();

        return new RedirectResponse($session->get('client/base_url') . '/helpdesk/customer-portal');
    }
}
