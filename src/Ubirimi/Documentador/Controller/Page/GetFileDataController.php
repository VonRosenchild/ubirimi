<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;

class GetFileDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        $fileId = $request->request->get('id');
        $file = $this->getRepository(Entity::class)->getFileById($fileId);

        $revisions = $this->getRepository(Entity::class)->getRevisionsByFileId($fileId);

        return $this->render(__DIR__ . '/../../../Resources/views/page//GetFileData.php', get_defined_vars());
    }
}