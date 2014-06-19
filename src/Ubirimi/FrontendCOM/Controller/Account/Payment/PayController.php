<?php

namespace Ubirimi\FrontendCOM\Controller\Account\Payment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Payment as PaymentRepository;

class PayController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $page = 'account_pay';

        $payment = PaymentRepository::getCurrentMonthPayment($session->get('client/id'));
        $paymentDate = new \DateTime($payment['date_created'], new \DateTimeZone('Europe/Bucharest'));

        if (null !== $payment) {
            $content = 'account/payment/PaymentDone.php';
        } else {
            $paymentUtil = new \Ubirimi\PaymentUtil();
            $amount = $paymentUtil->getAmount($session->get('client/id'));
            $content = 'account/payment/Pay.php';
        }

        return $this->render(__DIR__ . '/../../../Resources/views/_main.php', get_defined_vars());
    }
}
