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

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $duplicateOrganization = false;

        $organizationId = $request->get('id');
        $organization = Organization::getById($organizationId);

        if ($request->request->has('edit_organization')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $organizationDuplicate = Organization::getByName(
                $session->get('client/id'), mb_strtolower($name),
                $organizationId
            );

            if ($organizationDuplicate) {
                $duplicateOrganization = true;
            }

            if (!$emptyName && !$organizationDuplicate) {
                $currentDate = Util::getServerCurrentDateTime();
                Organization::updateById($organizationId, $name, $description, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_HELP_DESK,
                    $session->get('user/id'),
                    'UPDATE Organization ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/helpdesk/administration/organizations');
            }
        }

        $menuSelectedCategory = 'helpdesk_organizations';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME. ' / Create Organization';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/organization/EditOrganization.php', get_defined_vars());
    }
}
