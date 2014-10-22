<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class SearchController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $sectionPageTitle = $session->get('client/settings/title_name')
                . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME
                . ' / Search';
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;

            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME . ' / Search';
        }

        if ($request->request->has('search')) {
            $searchQuery = $request->request->get('keyword');

            return new RedirectResponse('/documentador/search?search_query=' . $searchQuery);
        }

        $searchQuery = $request->get('search_query');
        $menuSelectedCategory = 'documentator';

        $pages = $this->getRepository('documentador.space.space')->searchForPages($session->get('client/id'), $searchQuery);

        return $this->render(__DIR__ . '/../Resources/views/Search.php', get_defined_vars());
    }
}
