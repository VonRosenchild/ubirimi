<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeScreenSchemeDataId = $request->get('id');
        $screenSchemes = ScreenScheme::getMetaDataByClientId($session->get('client/id'));
        $issueTypeScreenSchemeData = TypeScreenScheme::getDataById($issueTypeScreenSchemeDataId);

        $screenSchemeId = $issueTypeScreenSchemeData['issue_type_screen_scheme_id'];
        $issueTypeScreenSchemeMetaData = TypeScreenScheme::getMetaDataById($screenSchemeId);

        if ($issueTypeScreenSchemeMetaData['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_issue_type_screen_scheme_data')) {
            $currentDate = Util::getServerCurrentDateTime();

            $screenSchemeId = Util::cleanRegularInputField($request->request->get('screen_scheme'));
            $issueTypeId = Util::cleanRegularInputField($request->request->get('issue_type'));

            TypeScreenScheme::updateDataById($screenSchemeId, $issueTypeId, $issueTypeScreenSchemeMetaData['id']);

            Log::add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Issue Type Screen Scheme Data ' . $issueTypeScreenSchemeMetaData['name'],
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/screen/configure-scheme-issue-type/' . $issueTypeScreenSchemeMetaData['id']);
        }
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme Data';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/EditData.php', get_defined_vars());
    }
}
