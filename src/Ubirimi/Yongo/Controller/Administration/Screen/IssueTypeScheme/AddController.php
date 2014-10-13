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
use Ubirimi\Yongo\Repository\Issue\Type;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        $allIssueTypes = Type::getAll($session->get('client/id'));

        if ($request->request->has('new_issue_type_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $issueTypeScreenScheme = new TypeScreenScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $issueTypeScreenSchemeId = $issueTypeScreenScheme->save($currentDate);

                $issueTypes = Type::getAll($session->get('client/id'));
                while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
                    TypeScreenScheme::addData($issueTypeScreenSchemeId, $issueType['id'], $currentDate);
                }

                $this->getRepository('ubirimi.general.log')->add(
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
