<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SecurityScheme;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        if ($request->request->has('add_issue_security_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $issueSecurityScheme = new SecurityScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $issueSecurityScheme->save($currentDate);

                return new RedirectResponse('/yongo/administration/issue-security-schemes');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/Add.php', get_defined_vars());
    }
}
