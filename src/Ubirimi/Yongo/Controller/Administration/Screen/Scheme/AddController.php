<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        $allScreens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));
        $allOperations = SystemOperation::getAll();

        if ($request->request->has('new_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $screenId = Util::cleanRegularInputField($request->request->get('screen'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $screenScheme = new ScreenScheme($session->get('client/id'), $name, $description);
                $screenSchemeId = $screenScheme->save($currentDate);
                while ($operation = $allOperations->fetch_array(MYSQLI_ASSOC)) {
                    $operationId = $operation['id'];
                    $this->getRepository(ScreenScheme::class)->addData($screenSchemeId, $operationId, $screenId, $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('client/id'),
                    'ADD Yongo Screen Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/schemes');
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/Add.php', get_defined_vars());
    }
}
