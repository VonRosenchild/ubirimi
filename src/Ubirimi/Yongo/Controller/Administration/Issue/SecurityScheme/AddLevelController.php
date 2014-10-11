<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SecurityScheme;
use Ubirimi\SystemProduct;

class AddLevelController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $issueSecuritySchemeId = $request->get('id');
        $issueSecurityScheme = SecurityScheme::getMetaDataById($issueSecuritySchemeId);
        if ($request->request->has('add_issue_security_scheme_level')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                SecurityScheme::addLevel($issueSecuritySchemeId, $name, $description, $currentDate);

                return new RedirectResponse('/yongo/administration/issue-security-scheme-levels/' . $issueSecuritySchemeId);
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme Level';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AddLevel.php', get_defined_vars());
    }
}
