<?php

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $notificationSchemeId = $request->get('id');
        $deletePossible = $request->get('delete_possible');

        if ($deletePossible) {
            return new Response('Are you sure you want to delete this notification scheme?');
        }

        return new Response('This notification scheme can no be deleted as it is associated with one or more projects.');
    }
}
