<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Status;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $statusExists = false;

        if ($request->request->has('new_status')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $status = $this->getRepository('yongo.issue.settings')->getByName($session->get('client/id'), 'status', mb_strtolower($name));

            if ($status)
                $statusExists = true;

            if (!$emptyName && !$statusExists) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository('yongo.issue.settings')->create(
                    'issue_status',
                    $session->get('client/id'),
                    $name,
                    $description,
                    null,
                    null,
                    $currentDate
                );

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Status ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/statuses');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Status';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/status/Add.php', get_defined_vars());
    }
}
