<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $screenSchemeId = $request->get('id');
        $screenScheme = $this->getRepository(ScreenScheme::class)->getMetaDataById($screenSchemeId);

        if ($screenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $screenSchemeData = $this->getRepository(ScreenScheme::class)->getDataByScreenSchemeId($screenSchemeId);
        $menuSelectedCategory = 'issue';
        $source = $request->get('source');
        $projectId = null;

        if ($source == 'project') {
            $projectId = $request->get('project_id');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/Configure.php', get_defined_vars());
    }
}
