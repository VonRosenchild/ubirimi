<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Configuration;
use Ubirimi\Yongo\Repository\Field\Field;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $fieldConfigurationId = $request->get('id');
        $fieldConfiguration = Configuration::getMetaDataById($fieldConfigurationId);

        if ($fieldConfiguration['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;

        $allFields = $this->getRepository('yongo.field.field')->getByClient($session->get('client/id'));
        $menuSelectedCategory = 'issue';

        $source = $request->get('source');
        $projectId = null;
        if ($source == 'project') {
            $projectId = $request->get('project_id');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field Configuration';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/Edit.php', get_defined_vars());
    }
}
