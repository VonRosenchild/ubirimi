<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PasswordRecoverDoController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $errorNotInClientDomain = false;
        $emailAddressNotExists = false;

        $httpHOST = Util::getHttpHost();

        $address = Util::cleanRegularInputField($request->request->get('address'));
        $exists = Util::checkEmailAddressExistence($address);

        if ($exists) {

            $baseURL = Util::getHttpHost();

            $userData = $this->getRepository(UbirimiUser::class)->getByEmailAddressAndBaseURL($address, $baseURL);

            if ($userData) {
                $password = Util::updatePasswordForUserId($userData['id']);

                $event = new UbirimiEvent(array('email' => $address, 'password' => $password));
                UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::PASSWORD_RECOVER, $event);

                $session->set('password_recover', true);
            } else {
                $errorNotInClientDomain = true;
            }
        } else {
            $emailAddressNotExists = true;
        }

        return $this->render(__DIR__ . '/../Resources/views/_passwordRecoverForm.php', get_defined_vars());
    }
}
