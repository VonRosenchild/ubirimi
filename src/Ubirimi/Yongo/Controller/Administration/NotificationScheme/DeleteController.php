<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\Scheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemeId = $request->request->get('id');
        $notificationScheme = Scheme::getMetaDataById($notificationSchemeId);

        Scheme::deleteDataByNotificationSchemeId($notificationSchemeId);
        Scheme::deleteById($notificationSchemeId);

        $currentDate = Util::getServerCurrentDateTime();
        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Notification Scheme ' . $notificationScheme['name'],
            $currentDate
        );

        return new Response('');
    }
}