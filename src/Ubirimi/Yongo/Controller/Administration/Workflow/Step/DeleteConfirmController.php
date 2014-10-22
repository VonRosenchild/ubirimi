<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Step;

use Guzzle\Http\Message\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $deletePossible = $request->get('delete_possible');
        if ($deletePossible)
            return new Response('Are you sure you want to delete this workflow step?');
        else {
            return new Response('This step has incoming transitions. It can not be deleted');
        }

    }
}