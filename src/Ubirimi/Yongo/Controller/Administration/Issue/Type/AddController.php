<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Settings;

use Ubirimi\SystemProduct;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $subTaskFlag = $request->query->has('type') ? 1 : 0;

        $emptyName = false;

        if ($request->request->has('new_type')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                $iconName = 'generic.png';
                $newIssueTypeId = $this->getRepository('yongo.issue.settings')->createIssueType($session->get('client/id'), $name, $description, $subTaskFlag, $iconName, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Type ' . $name,
                    $currentDate
                );

                if ($subTaskFlag) {
                    return new RedirectResponse('/yongo/administration/issue-sub-tasks');
                }

                return new RedirectResponse('/yongo/administration/issue-types');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/type/Add.php', get_defined_vars());
    }
}
