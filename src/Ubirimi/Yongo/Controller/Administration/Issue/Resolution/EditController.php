<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Resolution;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;

use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $issueResolution = $this->getRepository('yongo.issue.settings')->getById($Id, 'resolution');

        if ($issueResolution['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $resolutionExists = false;

        if ($request->request->has('edit_resolution')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $resolution = $this->getRepository('yongo.issue.settings')->getByName(
                $session->get('client/id'),
                'resolution',
                mb_strtolower($name),
                $Id
            );

            if ($resolution)
                $resolutionExists = true;

            if (!$resolutionExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.issue.settings')->updateById($Id, 'resolution', $name, $description, null, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Resolution ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/resolutions');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Resolution';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/resolution/Edit.php', get_defined_vars());
    }
}
