<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $tagId = $request->request->get('id');
        $tag = Tag::getById($tagId);
        $date = Util::getServerCurrentDateTime();

        Tag::deleteById($tagId);

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_QUICK_NOTES,
            $session->get('user/id'),
            'DELETE QUICK NOTES tag  ' . $tag['name'],
            $date
        );

        return new Response('');
    }
}
