<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\PostFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $deletable = $request->get('deletable');
        if ($deletable)
            return new Response('Are you sure you want to delete this post function?');
        else
            return new Response('This post function can not be deleted.');
    }
}