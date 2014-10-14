<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Category;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\Yongo\Repository\Project\Category;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('add_project_category')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;
            else {
                $data = Category::getByName($name, null, $session->get('client/id'));
                if ($data)
                    $duplicateName = true;
            }

            if (!$emptyName && !$duplicateName) {
                $projectCategory = new Category($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $projectCategory->save($currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Project Category ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/project/categories');
            }
        }
        $menuSelectedCategory = 'project';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Category';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/category/Add.php', get_defined_vars());
    }
}
