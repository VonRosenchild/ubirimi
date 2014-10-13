<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\User\User;

class ViewUserProfileController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'home';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

        $userId = $request->get('id');
        $user = $this->getRepository('ubirimi.user.user')->getById($userId);

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ViewUserProfile.php', get_defined_vars());
    }
}
