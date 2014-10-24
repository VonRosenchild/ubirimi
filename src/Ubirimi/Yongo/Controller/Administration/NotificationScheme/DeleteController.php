<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemeId = $request->request->get('id');
        $notificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataById($notificationSchemeId);

        $this->getRepository(NotificationScheme::class)->gdeleteDataByNotificationSchemeId($notificationSchemeId);
        $this->getRepository(NotificationScheme::class)->gdeleteById($notificationSchemeId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Notification Scheme ' . $notificationScheme['name'],
            $currentDate
        );

        return new Response('');
    }
}