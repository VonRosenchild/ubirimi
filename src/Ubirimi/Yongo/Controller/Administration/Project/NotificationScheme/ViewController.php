<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Issue\Event;
use Ubirimi\Yongo\Repository\Notification\Scheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = Project::getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $notificationSchemeId = $project['notification_scheme_id'];
        $notificationScheme = Scheme::getMetaDataById($notificationSchemeId);
        $events = Event::getByClient($session->get('client/id'));

        $hasGlobalAdministrationPermission = User::hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = User::hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Project Notification Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/View.php', get_defined_vars());
    }
}
