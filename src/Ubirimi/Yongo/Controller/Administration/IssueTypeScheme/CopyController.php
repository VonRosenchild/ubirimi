<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;
use Ubirimi\Repository\Log;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeSchemeId = $request->get('id');
        $type = $request->get('type');

        $issueTypeScheme = TypeScheme::getMetaDataById($issueTypeSchemeId);

        if ($issueTypeScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_issue_type_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateIssueTypeScheme = TypeScheme::getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateIssueTypeScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedIssueTypeScheme = new TypeScheme($session->get('client/id'), $name, $description, $type);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedIssueTypeSchemeId = $copiedIssueTypeScheme->save($currentDate);

                $issueTypeSchemeData = TypeScheme::getDataById($issueTypeSchemeId);

                while ($issueTypeSchemeData && $data = $issueTypeSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedIssueTypeScheme->addData($copiedIssueTypeSchemeId, $data['issue_type_id'], $currentDate);
                }

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Issue Type Scheme ' . $issueTypeScheme['name'],
                    $currentDate
                );

                if ('workflow' == $type) {
                    return new RedirectResponse('/yongo/administration/workflows/issue-type-schemes');
                }

                return new RedirectResponse('/yongo/administration/issue-type-schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Issue Type Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Copy.php', get_defined_vars());
    }
}



