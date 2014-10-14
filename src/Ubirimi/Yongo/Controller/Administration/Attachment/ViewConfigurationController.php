<?php

namespace Ubirimi\Yongo\Controller\Administration\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewConfigurationController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';
        $settings = $this->getRepository('ubirimi.general.client')->getYongoSettings($session->get('client/id'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Attachment Configuration';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/attachment/view_configuration.php', get_defined_vars());
    }
}