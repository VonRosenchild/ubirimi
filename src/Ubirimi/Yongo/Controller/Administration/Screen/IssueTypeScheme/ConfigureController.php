<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeScreenSchemeId = $request->get('id');
        $issueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataById($issueTypeScreenSchemeId);

        if ($issueTypeScreenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueTypeScreenSchemeData = IssueTypeScreenScheme::getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId);
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Configure.php', get_defined_vars());
    }
}
