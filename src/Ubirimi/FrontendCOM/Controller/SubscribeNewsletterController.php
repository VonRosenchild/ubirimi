<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Newsletter;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SubscribeNewsletterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if ($request->request->has('subscribe')) {
            $emailAddress = Util::cleanRegularInputField($request->request->get('email_address'));
            $validEmailAddress = Util::isValidEmail($emailAddress);

            if ($validEmailAddress) {
                $currentDate = Util::getServerCurrentDateTime();
                $isDuplicate = $this->getRepository(Newsletter::class)->checkEmailAddressDuplication($emailAddress);

                if (!$isDuplicate) {
                    $this->getRepository(Newsletter::class)->addSubscription($emailAddress, $currentDate);
                    return new RedirectResponse('/subscribe-newsletter-done');
                }
            }
        }

        $page = 'newsletter';
        $content = 'SubscribeNewsletter.php';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}