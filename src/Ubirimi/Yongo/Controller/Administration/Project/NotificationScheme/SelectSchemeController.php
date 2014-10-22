<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\Scheme;

class SelectSchemeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository('yongo.project.project')->getById($projectId);
        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('associate')) {

            $notificationSchemeId = $request->request->get('perm_scheme');

            $this->getRepository('yongo.project.project')->updateNotificationScheme($projectId, $notificationSchemeId);

            return new RedirectResponse('/yongo/administration/project/notifications/' . $projectId);
        }

        $notificationSchemes = Scheme::getByClientId($session->get('client/id'));

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Project Notification Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/Select.php', get_defined_vars());
    }
}
