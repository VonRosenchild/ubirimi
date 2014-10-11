<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\Scheme;
use Ubirimi\Repository\Log;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemeId = $request->get('id');
        $notificationScheme = Scheme::getMetaDataById($notificationSchemeId);

        if ($notificationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_notification_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateNotificationScheme = Scheme::getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateNotificationScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedNotificationScheme = new Scheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedNotificationSchemeId = $copiedNotificationScheme->save($currentDate);

                $notificationSchemeData = Scheme::getDataByNotificationSchemeId($notificationSchemeId);
                while ($notificationSchemeData && $data = $notificationSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedNotificationScheme->addDataRaw(
                        $copiedNotificationSchemeId,
                        $data['event_id'],
                        $data['permission_role_id'],
                        $data['group_id'],
                        $data['user_id'],
                        $data['current_assignee'],
                        $data['reporter'],
                        $data['current_user'],
                        $data['project_lead'],
                        $data['component_lead'],
                        $currentDate
                    );
                }

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Notification Scheme ' . $notificationScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/notification-schemes');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Notification Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/notification_scheme/Copy.php', get_defined_vars());
    }
}