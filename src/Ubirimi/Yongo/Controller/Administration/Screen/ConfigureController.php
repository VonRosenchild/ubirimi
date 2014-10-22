<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $screenId = $request->get('id');

        $screenMetadata = $this->getRepository('yongo.screen.screen')->getMetaDataById($screenId);
        if ($screenMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $position = $request->get('position');
        $fieldId = $request->get('field_id');

        if ($fieldId && $position) {
            $this->getRepository('yongo.screen.screen')->updatePositionForField($screenId, $fieldId, $position);

            return new RedirectResponse('/yongo/administration/screen/configure/' . $screenId);
        }

        $fields = $this->getRepository('yongo.field.field')->getByClient($session->get('client/id'));

        if ($request->request->has('add_screen_field')) {
            $fieldId = Util::cleanRegularInputField($request->request->get('field'));

            if ($fieldId != -1) {
                $currentDate = Util::getServerCurrentDateTime();
                $lastOrder = $this->getRepository('yongo.screen.screen')->getLastOrderNumber($screenId);
                $this->getRepository('yongo.screen.screen')->addData($screenId, $fieldId, ($lastOrder + 1), $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Screen Data ' . $screenMetadata['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screen/configure/' . $screenId);
            }
        }

        $screenData = $this->getRepository('yongo.screen.screen')->getDataById($screenId);
        $menuSelectedCategory = 'issue';

        $source = $request->get('source');
        $projectId = null;
        if ($source == 'project_screen' || $source == 'project_field') {
            $projectId = $request->get('project_id');
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Configure.php', get_defined_vars());
    }
}
