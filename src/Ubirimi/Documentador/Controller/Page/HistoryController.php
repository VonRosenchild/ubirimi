<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class HistoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'documentator';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

        $entityId = $_GET['id'];
        $page = Entity::getById($entityId, $loggedInUserId);

        $spaceId = $page['space_id'];
        $space = Space::getById($spaceId);
        $revisions = Entity::getRevisionsByPageId($entityId);

        $revisionCount = ($revisions) ? $revisions->num_rows + 1 : 1;

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / History';

        require_once __DIR__ . '/../../Resources/views/page/History.php';
    }
}
