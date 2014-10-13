<?php

namespace Ubirimi\Yongo\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $group = $this->getRepository('ubirimi.user.group')->getMetadataById($Id);

        if ($group['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $name = $group['name'];
        $description = $group['description'];

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('update_group')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository('ubirimi.user.group')->getByNameAndProductId(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    mb_strtolower($name),
                    $Id
                );

                if ($groupAlreadyExists)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('ubirimi.user.group')->updateById($Id, $name, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Group ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/groups');
            }
        }

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Group';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/Edit.php', get_defined_vars());
    }
}