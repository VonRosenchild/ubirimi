<?php

use Ubirimi\PaymentUtil;


/* check locking mechanism */
if (file_exists(__DIR__ .'/generate_invoice.lock')) {
    $fp = fopen('generate_invoice.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for generate_invoice task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

/*
 * this cronjob generates a pdf invoice for every paying client
 */

$clients = $this->getRepository('ubirimi.general.client')->getAll();

$invoiceUtil = new \Ubirimi\InvoiceUtil();
$paymentUtil = new PaymentUtil();

while ($client = $clients->fetch_array(MYSQLI_ASSOC)) {
    if ($client['is_payable'] && $client['paymill_id']) {
        $dayClientCreated = substr($client['date_created'], 8, 2);

        // do not generate an invoice in the first month of usage
        if (substr($client['date_created'], 0, 7) != date('Y-m')) {
            // get last invoice for the current month
            $lastInvoice = $invoiceUtil->getLastByClientIdAndMonthAndYear($client['id'], date('m'), date('Y'));
            if (!$lastInvoice) {
                $lastInvoice = $invoiceUtil->getLast();
                $lastNumber = $lastInvoice['number'];
                $lastNumber++;

                if (checkdate(date('n'), $dayClientCreated, date('Y'))) {
                    $invoiceDate = date('Y-m') . '-' . $dayClientCreated;
                } else {
                    $dayClientCreated--;
                    $invoiceDate = date('Y-m') . '-' . $dayClientCreated;
                }

                $amount = $paymentUtil->getAmountByClientId($client['id']);
                $VAT = 0;
                if (in_array($client['sys_country_id'], array_keys(PaymentUtil::$VATValuePerCountry))) {
                    $VAT = $amount * PaymentUtil::$VATValuePerCountry[$client['sys_country_id']] / 100;
                }
                $totalToBeCharged = $amount + $VAT;

                $invoiceUtil->generate($client['id'], $totalToBeCharged, $lastNumber, $invoiceDate);
            }
        }
    }
}