<?php

namespace Ubirimi\Documentador\Controller\Administration\GlobalPermissions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_users';
        $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
        $session->set('documentator/settings', $documentatorSettings);

        $users = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
        $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $globalsPermissions = $this->getRepository('yongo.permission.globalPermission')->getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/globalpermissions/View.php', get_defined_vars());
    }
}