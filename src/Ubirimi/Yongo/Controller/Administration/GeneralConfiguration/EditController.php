<?php

namespace Ubirimi\Yongo\Controller\Administration\GeneralConfiguration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';
        $clientSettings = $this->getRepository(UbirimiClient::class)->getYongoSettings($session->get('client/id'));

        if ($request->request->has('update_configuration')) {

            $allowUnassignedIssuesFlag = $request->request->get('allow_unassigned_issues');

            $parameters = array(
                array(
                    'field' => 'allow_unassigned_issues_flag',
                    'value' => $allowUnassignedIssuesFlag,
                    'type' => 'i'
                )
            );

            $this->getRepository(UbirimiClient::class)->updateProductSettings($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO, $parameters);

            $currentDate = Util::getServerCurrentDateTime();

            $this->getRepository(UbirimiLog::class)->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo GeneralSettings Settings',
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/general-configuration');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update GeneralSettings Configuration';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/general_configuration/Edit.php', get_defined_vars());
    }
}