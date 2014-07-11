<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Status;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteNotPossibleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return new Response(
            '<div>This status can not be deleted.</div>' .
            '<div>To delete a status, it must not be associated with a workflow.</div>'
        );
    }
}
