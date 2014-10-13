<?php

namespace Ubirimi\Yongo\Controller\Administration\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Event;
use Ubirimi\Repository\Log;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';
        $emptyName = false;

        $eventId = $request->get('id');
        $event = Event::getById($eventId);

        if ($event['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_event')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                Event::updateById($eventId, $name, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Event ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/events');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Event';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/event/edit.php', get_defined_vars());
    }
}