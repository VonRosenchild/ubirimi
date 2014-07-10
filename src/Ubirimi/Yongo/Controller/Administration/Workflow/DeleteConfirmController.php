<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $deletePossible = $request->get('delete_possible');

        if ($deletePossible) {
            return new Response('Are you sure you want to delete this workflow?');
        }

        return new Response('This workflow can not be deleted. It is associated with one or more workflow schemes.');
    }
}
