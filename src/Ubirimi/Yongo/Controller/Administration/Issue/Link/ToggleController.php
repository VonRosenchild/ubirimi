<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ToggleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $this->getRepository('ubirimi.general.client')->toggleIssueLinkingFeature($session->get('client/id'));

        $session->set('yongo/settings/issue_linking_flag', 1 - $session->get('yongo/settings/issue_linking_flag'));

        $logText = 'Activate';
        if (0 == $session->get('yongo/settings/issue_linking_flag')) {
            $logText = 'Deactivate';
        }

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            $logText . ' Yongo Issue Linking',
            $currentDate
        );

        return new RedirectResponse('/yongo/administration/issue-features/linking');
    }
}
