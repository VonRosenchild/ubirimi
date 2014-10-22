<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ValidateTimeSpentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $time = trim($request->request->get('time'));

        $valid = true;
        $time = str_replace(" ", '', $time);

        $count = '';
        $parts = '';
        for ($i = 0; $i < strlen($time); $i++) {
            if (!in_array($time[$i], array('w', 'd', 'h', 'm'))) {
                $count .= $time[$i];
            } else {
                if (!is_numeric($count)) {
                    $valid = false;
                    break;
                }
                $parts .= $time[$i];
                $count = '';
            }

            if ($count != '' && !is_numeric($count)) {
                $valid = false;
                break;
            }
        }

        for ($i = 0; $i < strlen($parts); $i++) {
            if (!in_array($parts[$i], array('w', 'd', 'h', 'm'))) {
                $valid = false;
                break;
            }
        }

        if ($valid)
            return new Response("ok");
        else
            return new Response("error");
    }
}