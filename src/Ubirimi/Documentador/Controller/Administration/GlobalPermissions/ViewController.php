<?php

namespace Ubirimi\Documentador\Controller\Administration\GlobalPermissions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_users';
        $documentatorSettings = $this->getRepository(UbirimiClient::class)->getDocumentatorSettings($clientId);
        $session->set('documentator/settings', $documentatorSettings);

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $globalsPermissions = $this->getRepository(GlobalPermission::class)->getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/globalpermissions/View.php', get_defined_vars());
    }
}