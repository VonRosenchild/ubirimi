<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Type;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueType;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $issueType = IssueType::getById($Id);

        if ($issueType['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $typeExists = false;

        if ($request->request->has('edit_type')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $type = IssueSettings::getByName($session->get('client/id'), 'type', mb_strtolower($name), $Id);
            if ($type)
                $typeExists = true;

            if (!$typeExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                IssueType::updateById($Id, $name, $description, $currentDate);

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Type ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue-sub-tasks');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/type/Edit.php', get_defined_vars());
    }
}
