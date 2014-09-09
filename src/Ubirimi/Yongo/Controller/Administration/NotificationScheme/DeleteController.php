<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemeId = $request->request->get('id');
        $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);

        NotificationScheme::deleteDataByNotificationSchemeId($notificationSchemeId);
        NotificationScheme::deleteById($notificationSchemeId);

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