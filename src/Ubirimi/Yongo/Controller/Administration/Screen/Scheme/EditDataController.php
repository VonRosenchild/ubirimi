<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Scheme;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $screenSchemeDataId = $request->get('id');

        $screens = $this->getRepository('yongo.screen.screen')->getAll($session->get('client/id'));
        $screenSchemeData = Scheme::getDataByScreenDataId($screenSchemeDataId);
        $screenSchemeId = $screenSchemeData['screen_scheme_id'];
        $operationId = $screenSchemeData['sys_operation_id'];
        $selectedScreenId = $screenSchemeData['screen_id'];
        $screenSchemeMetaData = Scheme::getMetaDataById($screenSchemeData['screen_scheme_id']);

        if ($screenSchemeMetaData['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_screen_scheme')) {
            $screenId = Util::cleanRegularInputField($request->request->get('screen'));
            $operationId = Util::cleanRegularInputField($request->request->get('operation'));

            Scheme::updateDataById($screenSchemeId, $operationId, $screenId);

            return new RedirectResponse('/yongo/administration/screen/configure-scheme/' . $screenSchemeId);
        }
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/EditData.php', get_defined_vars());
    }
}
