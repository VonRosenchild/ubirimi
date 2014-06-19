<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ContactSendController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $errors = array('empty_email' => false,
            'email_not_valid' => false,
            'empty_message' => false);

        $name = Util::cleanRegularInputField($_POST['name']);
        $email = Util::cleanRegularInputField($_POST['email']);
        $category = Util::cleanRegularInputField($_POST['category']);
        $message = Util::cleanRegularInputField($_POST['message']);

        if (empty($message)) {
            $errors['empty_message'] = true;
        }

        if (empty($email)) {
            $errors['empty_email'] = true;
        }
        else if (!Util::isValidEmail($email)) {
            $errors['email_not_valid'] = true;
        }

        if (Util::hasNoErrors($errors)) {
            $event = new UbirimiEvent(array('name' => $name, 'category' => $category, 'message' => $message, 'email' => $email));
            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::CONTACT, $event);

            $_POST = array();

            $name = $email = $category = $message = null;

            $session->set('contact_send', true);
        }

        return $this->render(__DIR__ . '/../Resources/views/_contactForm.php', get_defined_vars());
    }
}