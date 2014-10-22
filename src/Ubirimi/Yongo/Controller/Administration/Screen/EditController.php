<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class EditController extends UbirimiController
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
            $screen_row_exists = $this->getRepository('yongo.screen.screen')->getByNameAndId($session->get('client/id'), mb_strtolower($name), $screenId);

            if ($screen_row_exists)
                $screenExists = true;

            if (!$screenExists && !$emptyScreenName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.screen.screen')->updateMetadataById($screenId, $name, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Screen ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Edit.php', get_defined_vars());
    }
}
