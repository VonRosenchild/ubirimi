<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $type = $request->get('type');
        if ($type == 'project') {
            $buttonLabel = 'Create Issue Type Scheme';
        } else {
            $buttonLabel = 'Create Workflow Issue Type Scheme';
        }

        $emptyIssueTypeName = false;
        $issueTypeExists = false;

        $allIssueTypes = $this->getRepository(IssueType::class)->getAll($session->get('client/id'));

        if ($request->request->has('new_type_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyIssueTypeName = true;
            }

            if (!$emptyIssueTypeName) {

                $issueTypeScheme = new IssueTypeScheme($session->get('client/id'), $name, $description, $type);
                $currentDate = Util::getServerCurrentDateTime();
                $issueTypeSchemeId = $issueTypeScheme->save($currentDate);

                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 11) == 'issue_type_') {
                        $issueTypeId = str_replace('issue_type_', '', $key);
                        $this->getRepository(IssueTypeScheme::class)->addData($issueTypeSchemeId, $issueTypeId, $currentDate);
                    }
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Type Scheme ' . $name,
                    $currentDate
                );

                if ($type == 'project') {
                    return new RedirectResponse('/yongo/administration/issue-type-schemes');
                }

                return new RedirectResponse('/yongo/administration/workflows/issue-type-schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Add.php', get_defined_vars());
    }
}
