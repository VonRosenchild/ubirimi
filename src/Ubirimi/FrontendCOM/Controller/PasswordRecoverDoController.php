<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PasswordRecoverDoController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $emailAddress = Util::cleanRegularInputField($request->request->get('address'));

        $exists = Util::checkEmailAddressExistence($emailAddress);

        if ($exists) {
            $password = Util::updatePasswordForClientAdministrator($exists['id']);

            $event = new UbirimiEvent(array(
                'email' => $emailAddress,
                'password' => $password)
            );

            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::PASSWORD_RECOVER, $event);

            $session->set('password_recover', true);
        }

        return $this->render(__DIR__ . '/../Resources/views/_passwordRecoverForm.php', get_defined_vars());
    }
}
