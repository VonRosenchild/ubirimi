<?php

namespace Ubirimi\HelpDesk\Controller\Administration\Organization;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Organization\Organization;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $statusExists = false;

        if ($request->request->has('new_organization')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $organization = $this->getRepository(Organization::class)->getByName($session->get('client/id'), mb_strtolower($name));

            if ($organization)
                $statusExists = true;

            if (!$emptyName && !$statusExists) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(Organization::class)->create($session->get('client/id'), $name, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_HELP_DESK,
                    $session->has('user/id'),
                    'ADD Organization ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/helpdesk/administration/organizations');
            }
        }

        $menuSelectedCategory = 'helpdesk_organizations';
        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Create Organization';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/organization/AddOrganization.php', get_defined_vars());
    }
}
