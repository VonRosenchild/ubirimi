<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Repository\Log;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeScreenSchemeId = $request->get('id');
        $issueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataById($issueTypeScreenSchemeId);

        if ($issueTypeScreenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_issue_type_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateIssueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataByNameAndClientId($session->get('client/id'), mb_strtolower($name));
            if ($duplicateIssueTypeScreenScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedIssueTypeScreenScheme = new IssueTypeScreenScheme($session->get('client/id'), $name, $description);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedIssueTypeScreenSchemeId = $copiedIssueTypeScreenScheme->save($currentDate);

                $issueTypeScreenSchemeData = IssueTypeScreenScheme::getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId);

                while ($issueTypeScreenSchemeData && $data = $issueTypeScreenSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedIssueTypeScreenScheme->addDataComplete($copiedIssueTypeScreenSchemeId, $data['issue_type_id'], $data['screen_scheme_id'], $currentDate);
                }

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Issue Type Scheme ' . $issueTypeScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/issue-types');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Issue Type Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Copy.php', get_defined_vars());
    }
}
