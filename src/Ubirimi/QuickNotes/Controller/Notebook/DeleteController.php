<?php

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notebookId = $request->request->get('id');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository(Notebook::class)->deleteById($notebookId);

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_QUICK_NOTES,
            $session->get('user/id'),
            'DELETE QUICK NOTES notebook ' . $notebook['name'],
            $date
        );

        return new Response('');
    }
}
