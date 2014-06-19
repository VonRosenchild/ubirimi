<?php

namespace Ubirimi\FrontendCOM\Controller\Account\Payment;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\Log;
use Ubirimi\Container\UbirimiContainer;

class ProcessController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (isset($_POST['paymillToken'])) {
            try {
                $paymentRecord = UbirimiContainer::get()['payment.transaction']->execute(
                    $_POST['paymillToken'],
                    $session->get('client/id'),
                    $session->get('client/contact_email'),
                    $session->get('client/company_name')
                );

                Log::add(
                    $session->get('client/id'),
                    \Ubirimi\SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('client/id'),
                    sprintf('SUCCESS payment. id: %d', $paymentRecord['id']),
                    \Ubirimi\Util::getCurrentDateTime()
                );
            } catch (\Exception $e) {
                Log::add(
                    $session->get('client/id'),
                    \Ubirimi\SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('client/id'), sprintf('ERROR in payment. [%s]', $e->getMessage()),
                    \Ubirimi\Util::getCurrentDateTime()
                );

                $session->getFlashBag()->set('payment.transaction.failed', true);
            }

            return new RedirectResponse('/account/pay');
        }
    }
}

