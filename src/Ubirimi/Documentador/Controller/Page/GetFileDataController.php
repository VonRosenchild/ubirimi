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