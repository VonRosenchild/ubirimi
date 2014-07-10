<?php

namespace Ubirimi\Yongo\Controller\Administration\Field;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return new Response(
            '<div>Are you sure you want to delete this custom field?</div>' .
            '<div>Deleting a custom field removes all matching values from all issues.</div>'
        );
    }
}
