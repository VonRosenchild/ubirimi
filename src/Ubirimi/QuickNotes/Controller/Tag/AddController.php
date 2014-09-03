<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $date = Util::getServerCurrentDateTime();

        $name = $request->request->get('name');
        $description = $request->request->get('description');

        // check for duplicates in the user space
        $tagUserExists = Tag::getByNameAndUserId($session->get('user/id'), mb_strtolower($name));

        if (!$tagUserExists) {
            $tagId = Tag::add($session->get('user/id'), $name, $date);
            return new Response('1');
        }

        return new Response('0');
    }
}
