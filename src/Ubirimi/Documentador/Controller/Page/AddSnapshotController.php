<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddSnapshotController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $entityId = $request->request->get('id');
        $entityLastSnapshotId = $request->request->get('entity_last_snapshot_id');
        $newEntityContent = $request->request->get('content');
        $date = Util::getServerCurrentDateTime();

        $entity = $this->getRepository('documentador.entity.entity')->getById($entityId);
        $oldEntityContent = $entity['content'];

        $newEntityContent =  preg_replace("/[[:cntrl:]]/", "", $newEntityContent); ;
        $oldEntityContent =  preg_replace("/[[:cntrl:]]/", "", $oldEntityContent); ;

        if (md5($oldEntityContent) != md5($newEntityContent)) {
            $this->getRepository('documentador.entity.entity')->deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId, $entityLastSnapshotId);
            $this->getRepository('documentador.entity.entity')->addSnapshot($entityId, $newEntityContent, $loggedInUserId, $date);

            $now = date('Y-m-d H:i:s');
            $activeSnapshots = $this->getRepository('documentador.entity.entity')->getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');

            return new JsonResponse($activeSnapshots);
        }
    }
}