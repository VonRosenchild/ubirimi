<?php

namespace Ubirimi\Yongo\Controller\Administration\Project\Category;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Category;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectCategoryId = $request->request->get('id');
        $projectCategory = Category::getById($projectCategoryId);
        Category::deleteById($session->get('client/id'), $projectCategoryId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Project Category ' . $projectCategory['name'],
            $currentDate
        );

        return new Response('');
    }
}
