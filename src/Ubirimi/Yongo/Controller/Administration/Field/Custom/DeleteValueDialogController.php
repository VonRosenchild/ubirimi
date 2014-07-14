<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteValueDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        $html = 'Are you sure you want to delete this value?';
        $html .= '<br />';
        $html .= 'Note: Deleting a custom field option removes all matching values from all issues.';
        return new Response($html);
    }
}