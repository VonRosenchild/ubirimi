<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\Scheme;
use Ubirimi\Yongo\Repository\Issue\Event;
use Ubirimi\Yongo\Repository\Project\Project;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $notificationSchemeId = $request->get('id');
        $backLink = $request->get('back');
        $projectId = $request->get('project_id');

        $notificationScheme = $this->getRepository('yongo.notification.scheme')->ggetMetaDataById($notificationSchemeId);
        if ($notificationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($projectId) {
            $project = $this->getRepository('yongo.project.project')->getById($projectId);
            if ($project['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $events = Event::getByClient($session->get('client/id'));
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Notification Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/notification_scheme/EditData.php', get_defined_vars());
    }
}
