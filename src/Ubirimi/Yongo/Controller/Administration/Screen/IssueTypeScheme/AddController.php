<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        $allIssueTypes = IssueType::getAll($session->get('client/id'));

        if ($request->request->has('new_issue_type_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $issueTypeScreenScheme = new IssueTypeScreenScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $issueTypeScreenSchemeId = $issueTypeScreenScheme->save($currentDate);

                $issueTypes = IssueType::getAll($session->get('client/id'));
                while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
                    IssueTypeScreenScheme::addData($issueTypeScreenSchemeId, $issueType['id'], $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Type Screen Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/issue-types');
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Add.php', get_defined_vars());
    }
}
