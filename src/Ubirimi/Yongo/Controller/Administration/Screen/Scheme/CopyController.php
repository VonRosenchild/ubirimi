<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;


class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $screenSchemeId = $request->get('id');
        $screenScheme = ScreenScheme::getMetaDataById($screenSchemeId);

        if ($screenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;
        if ($request->request->has('copy_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $duplicateScreen = ScreenScheme::getMetaDataByNameAndClientId($session->get('client/id'), mb_strtolower($name));
            if ($duplicateScreen)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedScreenScheme = new ScreenScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedScreenSchemeId = $copiedScreenScheme->save($currentDate);

                $screenSchemeData = ScreenScheme::getDataByScreenSchemeId($screenSchemeId);
                while ($data = $screenSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedScreenScheme->addData($copiedScreenSchemeId, $data['sys_operation_id'], $data['screen_id'], $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Screen Scheme ' . $screenScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/schemes');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/Copy.php', get_defined_vars());
    }
}
