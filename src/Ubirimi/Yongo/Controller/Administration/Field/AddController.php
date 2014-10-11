<?php

namespace Ubirimi\Yongo\Controller\Administration\Field;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Custom;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $types = Custom::getTypes();
        $menuSelectedCategory = 'issue';

        $emptyType = false;
        if ($request->request->has('new_custom_field')) {
            $type = $request->request->get('type');
            if (!$type)
                $emptyType = true;
            else {
                return new RedirectResponse('/yongo/administration/custom-field/add-data/' . $type);
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Custom Field';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/field/Add.php', get_defined_vars());
    }
}
