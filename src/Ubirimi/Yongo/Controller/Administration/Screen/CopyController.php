<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $screenId = $request->get('id');
        $screen = $this->getRepository('yongo.screen.screen')->getMetaDataById($screenId);

        if ($screen['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyScreenName = false;
        $screenExists = false;

        if ($request->request->has('edit_workflow_screen')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyScreenName = true;

            // check for duplication
            $screen_row_exists = $this->getRepository('yongo.screen.screen')->getByName($session->get('client/id'), mb_strtolower($name));

            if ($screen_row_exists)
                $screenExists = true;

            if (!$screenExists && !$emptyScreenName) {
                $copiedScreen = new Screen($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedScreenId = $copiedScreen->save($currentDate);

                $screenData = $this->getRepository('yongo.screen.screen')->getDataById($screenId);
                while ($data = $screenData->fetch_array(MYSQLI_ASSOC)) {
                    $this->getRepository('yongo.screen.screen')->addData($copiedScreenId, $data['field_id'], $data['position'], $currentDate);
                }

                return new RedirectResponse('/yongo/administration/screens');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Copy.php', get_defined_vars());
    }
}
