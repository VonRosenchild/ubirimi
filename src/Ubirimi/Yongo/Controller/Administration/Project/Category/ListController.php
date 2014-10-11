<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Category;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Category;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectCategories = Category::getAll($session->get('client/id'));

        $menuSelectedCategory = 'project';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/category/List.php', get_defined_vars());
    }
}
