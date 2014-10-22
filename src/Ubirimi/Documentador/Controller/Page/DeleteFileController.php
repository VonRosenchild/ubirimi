<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteFileController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $fileId = $request->request->get('id');

        $file = $this->getRepository('documentador.entity.entity')->getFileById($fileId);
        $entityId = $file['documentator_entity_id'];

        $this->getRepository('documentador.entity.entity')->deleteFileById($entityId, $fileId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador file ' . $file['name'], $currentDate);

        return new Response('');
    }
}