<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $entityId = $request->get('id');

        $page = $this->getRepository('documentador.entity.entity')->getById($entityId, $loggedInUserId);
        $spaceId = $page['space_id'];
        $space = $this->getRepository('documentador.space.space')->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'documentator';

        $session->set('current_edit_entity_id', $entityId);
        $name = $page['name'];

        $now = date('Y-m-d H:i:s');
        $activeSnapshots = $this->getRepository('documentador.entity.entity')->getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');
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
        $lastUserSnapshot = $this->getRepository('documentador.entity.entity')->getLastSnapshot($entityId, $loggedInUserId);

        if ($request->get('edit_page')) {
            $name = $request->request->get('name');
            $content = $request->request->get('content');

            $date = Util::getServerCurrentDateTime();

            $this->getRepository('documentador.entity.entity')->addRevision($entityId, $loggedInUserId, $page['content'], $date);
            $this->getRepository('documentador.entity.entity')->updateById($entityId, $name, $content, $date);

            $this->getRepository('documentador.entity.entity')->deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

            $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'UPDATE Documentador entity ' . $name, $date);

            return new RedirectResponse('/documentador/page/view/' . $entityId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Update ' . $page['name'];

        return $this->render(__DIR__ . '/../../Resources/views/page/Edit.php', get_defined_vars());
    }
}