<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Status;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $issueStatus = $this->getRepository(IssueSettings::class)->getById($Id, 'status');

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
            $status = $this->getRepository(IssueSettings::class)->getByName(
                $session->get('client/id'),
                'status',
                mb_strtolower($name),
                $Id
            );

            if ($status)
                $statusExists = true;

            if (!$statusExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(IssueSettings::class)->updateById($Id, 'status', $name, $description, null, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
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
