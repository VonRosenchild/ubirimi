<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;

class DeleteFieldController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $screenDataId = $request->request->get('screen_data_id');

        Screen::deleteDataById($screenDataId);

        return new Response('');
    }
}
