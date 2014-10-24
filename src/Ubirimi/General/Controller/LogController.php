<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class LogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_overview';

        $from = $request->get('from');
        $to = $request->get('to');

        $logs = $this->getRepository(UbirimiLog::class)->getByClientIdAndInterval($clientId, $from, $to);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Logs';

        return $this->render(__DIR__ . '/../Resources/views/Log.php', get_defined_vars());
    }
}