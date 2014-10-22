<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Type;
use Ubirimi\Yongo\Repository\Issue\TypeScheme;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeSchemeId = $request->get('id');

        $emptyName = false;
        $typeExists = false;

        $issueTypeScheme = TypeScheme::getMetaDataById($issueTypeSchemeId);

        if ($issueTypeScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $allIssueTypes = Type::getAll($session->get('client/id'));
        $schemeIssueTypes = TypeScheme::getDataById($issueTypeSchemeId);

        $type = $issueTypeScheme['type'];
        $name = $issueTypeScheme['name'];
        $description = $issueTypeScheme['description'];

        if ($request->request->has('edit_type_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                TypeScheme::updateMetaDataById($issueTypeSchemeId, $name, $description);
                TypeScheme::deleteDataByIssueTypeSchemeId($issueTypeSchemeId);
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
                    'UPDATE Yongo Issue Type Scheme ' . $name,
                    $currentDate
                );

                if ($type == 'project') {
                    return new RedirectResponse('/yongo/administration/issue-type-schemes');
                }

                return new RedirectResponse('/yongo/administration/workflows/issue-type-schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Edit.php', get_defined_vars());
    }
}
