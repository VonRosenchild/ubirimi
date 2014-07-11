<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

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
            return new Response('Are you sure you want to delete this field configuration?');
        }

        return new Response('This field configuration can not be deleted. It is associated with a field configuration scheme.');
    }
}
