<?php

namespace Ubirimi\FrontendCOM\Controller\Account\Payment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Invoice as InvoiceRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class InvoiceController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $content = 'account/payment/Invoice.php';
        $page = 'account_invoice';

        $invoices = InvoiceRepository::last12($session->get('client/id'));

        return $this->render(__DIR__ . '/../../../Resources/views/_main.php', get_defined_vars());
    }
}