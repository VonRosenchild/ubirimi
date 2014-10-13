<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Field\Field;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $menuSelectedCategory = 'issue';
        $emptyName = false;

        $fields = $this->getRepository('yongo.field.field')->getByClient($session->get('client/id'));

        if ($request->request->has('add_screen')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $screen = new Screen($session->get('client/id'), $name, $description);
                $screenId = $screen->save($currentDate);

                $order = 0;
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 6) == 'field_') {
                        $order++;
                        $fieldId = str_replace('field_', '', $key);
                        Screen::addData($screenId, $fieldId, $order, $currentDate);
                    }
                }

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Screen ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens');
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Add.php', get_defined_vars());
    }
}
