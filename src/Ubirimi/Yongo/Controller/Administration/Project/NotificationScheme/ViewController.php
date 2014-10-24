<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $notificationSchemeId = $project['notification_scheme_id'];
        $notificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataById($notificationSchemeId);
        $events = $this->getRepository(IssueEvent::class)->getByClient($session->get('client/id'));

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Project Notification Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/View.php', get_defined_vars());
    }
}
