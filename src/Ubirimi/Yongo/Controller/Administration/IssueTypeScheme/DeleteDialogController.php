<?php

namespace Ubirimi\Yongo\Controller\Administration\IssueTypeScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $deletePossible = $request->get('delete_possible');

        if ($deletePossible) {
            return new Response('Are you sure you want to delete this issue type scheme?');
        }

        return new Response('This issue type scheme can not be deleted. It is associated with one or more projects.');
    }
}
