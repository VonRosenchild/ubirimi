<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;

use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $tagId = $request->request->get('id');
        $tag = $this->getRepository(Tag::class)->getById($tagId);
        $date = Util::getServerCurrentDateTime();

        $this->getRepository(Tag::class)->deleteById($tagId);

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_QUICK_NOTES,
            $session->get('user/id'),
            'DELETE QUICK NOTES tag  ' . $tag['name'],
            $date
        );

        return new Response('');
    }
}
