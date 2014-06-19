<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class ContactController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->remove('contact_send');

        $errors = array('empty_email' => false,
            'email_not_valid' => false,
            'empty_message' => false);

        $content = 'Contact.php';
        $page = 'contact';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
