<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Resolution;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;


class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $resolutionExists = false;

        if ($request->request->has('new_resolution')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $resolution = $this->getRepository(IssueSettings::class)->getByName($session->get('client/id'), 'resolution', mb_strtolower($name));
            if ($resolution)
                $resolutionExists = true;

            if (!$resolutionExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(IssueSettings::class)->create(
                    'issue_resolution',
                    $session->get('client/id'),
                    $name,
                    $description,
                    null,
                    null,
                    $currentDate
                );

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Resolution ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/resolutions');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Resolution';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/resolution/Add.php', get_defined_vars());
    }
}
