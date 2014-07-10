<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;
use Ubirimi\Repository\Log;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $screenSchemeId = $request->get('id');

        $emptyName = false;
        $screenScheme = ScreenScheme::getMetaDataById($screenSchemeId);

        if ($screenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                ScreenScheme::updateMetaDataById($screenSchemeId, $name, $description, $currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Screen Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/Edit.php', get_defined_vars());
    }
}
