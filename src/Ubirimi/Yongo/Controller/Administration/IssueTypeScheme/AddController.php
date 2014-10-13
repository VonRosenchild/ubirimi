<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Issue\Type;

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

        $allIssueTypes = Type::getAll($session->get('client/id'));

        if ($request->request->has('new_type_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyIssueTypeName = true;
            }

            if (!$emptyIssueTypeName) {

                $issueTypeScheme = new TypeScheme($session->get('client/id'), $name, $description, $type);
                $currentDate = Util::getServerCurrentDateTime();
                $issueTypeSchemeId = $issueTypeScheme->save($currentDate);

                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 11) == 'issue_type_') {
                        $issueTypeId = str_replace('issue_type_', '', $key);
                        TypeScheme::addData($issueTypeSchemeId, $issueTypeId, $currentDate);
                    }
                }

                $this->getRepository('ubirimi.general.log')->add(
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
