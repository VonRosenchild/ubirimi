<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Priority;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $issuePriority = Settings::getById($Id, 'priority');

        if ($issuePriority['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $priorityExists = false;

        if ($request->request->has('edit_priority')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = $request->request->get('color');

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $priority = Settings::getByName(
                $session->get('client/id'),
                'priority',
                mb_strtolower($name),
                $Id
            );

            if ($priority)
                $priorityExists = true;

            if (!$priorityExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                Settings::updateById($Id, 'priority', $name, $description, $color, $currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Priority ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/priorities');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Priority';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/priority/Edit.php', get_defined_vars());
    }
}
