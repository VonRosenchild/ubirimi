<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\Scheme;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemes = Scheme::getByClientId($session->get('client/id'));
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Notification Schemes';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/notification_scheme/List.php', get_defined_vars());
    }
}
