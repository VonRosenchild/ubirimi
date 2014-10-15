<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $source_application = 'documentator';

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $entityId = $_GET['id'];

        $page = Entity::getById($entityId, $loggedInUserId);
        $spaceId = $page['space_id'];
        $space = Space::getById($spaceId);

        if ($space['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $menuSelectedCategory = 'documentator';

        $session->set('current_edit_entity_id', $entityId);

        $name = $page['name'];

        $now = date('Y-m-d H:i:s');
        $activeSnapshots = Entity::getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');
        $textWarningMultipleEdits = null;
        if ($activeSnapshots) {
            $textWarningMultipleEdits = 'This page is being edited by ';
            $usersUsing = array();
            for ($i = 0; $i < count($activeSnapshots); $i++) {
                if ($activeSnapshots[$i]['last_edit_offset'] <= 1) {
                    $usersUsing[] = '<a href="/documentador/user/profile/' . $activeSnapshots[$i]['user_id'] . '">' . $activeSnapshots[$i]['first_name'] . ' ' . $activeSnapshots[$i]['last_name'] . '</a>';
                }
            }

            $textWarningMultipleEdits .= implode(', ', $usersUsing);
        }

        // see if the user editing the page has a draft saved
        $lastUserSnapshot = Entity::getLastSnapshot($entityId, $loggedInUserId);

        if (isset($_POST['edit_page'])) {
            $name = $_POST['name'];
            $content = $_POST['content'];

            $date = Util::getServerCurrentDateTime();

            Entity::addRevision($entityId, $loggedInUserId, $page['content'], $date);
            Entity::updateById($entityId, $name, $content, $date);

            Entity::deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

            $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'UPDATE Documentador entity ' . $name, $date);

            header('Location: /documentador/page/view/' . $entityId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Update ' . $page['name'];

        require_once __DIR__ . '/../../Resources/views/page/Edit.php';
    }
}