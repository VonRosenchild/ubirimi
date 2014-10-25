<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;


class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeSchemeId = $request->get('id');
        $type = $request->get('type');

        $issueTypeScheme = $this->getRepository(IssueTypeScheme::class)->getMetaDataById($issueTypeSchemeId);

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

            $duplicateIssueTypeScheme = $this->getRepository(IssueTypeScheme::class)->getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateIssueTypeScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedIssueTypeScheme = new IssueTypeScheme($session->get('client/id'), $name, $description, $type);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedIssueTypeSchemeId = $copiedIssueTypeScheme->save($currentDate);

                $issueTypeSchemeData = $this->getRepository(IssueTypeScheme::class)->getDataById($issueTypeSchemeId);

                while ($issueTypeSchemeData && $data = $issueTypeSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedIssueTypeScheme->addData($copiedIssueTypeSchemeId, $data['issue_type_id'], $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
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



