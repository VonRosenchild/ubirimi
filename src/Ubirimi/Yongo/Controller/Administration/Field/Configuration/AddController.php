<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Repository\Log;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        $emptyName = false;

        if ($request->request->has('add_field_configuration')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $fieldConfiguration = new FieldConfiguration($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $fieldConfiguration->save($currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Field Configuration ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/field-configurations');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Add Field Configuration Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/Add.php', get_defined_vars());
    }
}
