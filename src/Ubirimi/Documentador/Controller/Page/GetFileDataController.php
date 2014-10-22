<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class GetFileDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        $fileId = $request->request->get('id');
        $file = $this->getRepository('documentador.entity.entity')->getFileById($fileId);

        $revisions = $this->getRepository('documentador.entity.entity')->getRevisionsByFileId($fileId);

        return $this->render(__DIR__ . '/../../../Resources/views/page//GetFileData.php', get_defined_vars());
    }
}