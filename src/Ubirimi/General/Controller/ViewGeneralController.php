<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;

class ViewGeneralController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);

        $menuSelectedCategory = 'general_overview';
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));
        $client = $this->getRepository('ubirimi.general.client')->getById($session->get('client/id'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings';

        return $this->render(__DIR__ . '/../Resources/views/ViewGeneral.php', get_defined_vars());
    }
}
