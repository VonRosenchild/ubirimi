<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;

class EditPreferenceController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $settings = $this->getRepository('ubirimi.general.client')->getYongoSettings($session->get('client/id'));
        $menuSelectedCategory = 'user';

        if ($request->request->has('edit_settings')) {
            $issuesPerPage = $request->request->get('issues_per_page');
            $notifyOwnChanges = $request->request->get('notify_own_changes');

            $parameters = array(
                array('field' => 'issues_per_page', 'value' => $issuesPerPage, 'type' => 'i'),
                array('field' => 'notify_own_changes_flag', 'value' => $notifyOwnChanges, 'type' => 'i')
            );

            $this->getRepository('ubirimi.general.client')->updateProductSettings(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $parameters
            );

            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository('ubirimi.general.log')->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Global User Preferences',
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/user-preference');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update User Preferences';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/EditPreference.php', get_defined_vars());
    }
}
