<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Status;

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
        $issueStatus = Settings::getById($Id, 'status');

        if ($issueStatus['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $statusExists = false;

        if ($request->request->has('edit_status')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $status = Settings::getByName(
                $session->get('client/id'),
                'status',
                mb_strtolower($name),
                $Id
            );

            if ($status)
                $statusExists = true;

            if (!$statusExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                Settings::updateById($Id, 'status', $name, $description, null, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Status ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/statuses');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Status';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/status/Edit.php', get_defined_vars());
    }
}
