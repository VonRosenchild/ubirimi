<?php

namespace Ubirimi\Yongo\Controller\Administration\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Event;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $eventId = $request->request->get('id');

        $event = Event::getById($eventId);

        Event::deleteById($eventId);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Event ' . $event['name'],
            $currentDate
        );

        return new Response('');
    }
}