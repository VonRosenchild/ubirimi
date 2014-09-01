<?php

namespace Ubirimi\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Ubirimi\Repository\Payment;

class UbirimiPaymentDueNotifierListener
{
    public function paymentDueNotifier(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $clientId = $event->getRequest()->getSession()->get('client/id');
        $isPayable = $event->getRequest()->getSession()->get('client/is_payable');

        if (0 == $isPayable) {
            return true;
        }

        if (!($response instanceof RedirectResponse)) {
            /* check if the client had a successful payment for the previous month */
            if (null !== Payment::getCurrentMonthPayment($clientId) ||
                null !== Payment::getPreviousMonthPayment($clientId)) {

                return true;
            }

            $content = $response->getContent();

            $notifyMessage = file_get_contents(__DIR__ . '/../General/Resources/views/_paymentDueMessage.php');

            $content = str_replace(
                '<div class="warningMessage"></div>',
                '<div class="warningMessage">'. $notifyMessage . '</div>',
                $content
            );

            $response->setContent($content);
        }
    }
}
