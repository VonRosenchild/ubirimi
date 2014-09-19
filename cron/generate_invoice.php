<?php

/* check locking mechanism */
if (file_exists('generate_invoice.lock')) {
    $fp = fopen('generate_invoice.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for generate_invoice task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$clients = \Ubirimi\Repository\Client::getAll();

$invoiceUtil = new \Ubirimi\InvoiceUtil();
$paymentUtil = new \Ubirimi\PaymentUtil();

while ($client = $clients->fetch_array(MYSQLI_ASSOC)) {
    $dayClientCreated = substr($client['date_created'], 8, 2);

    // do not generate an invoice in the first month of usage
    if (substr($client['date_created'], 0, 7) != date('Y-m')) {
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
            $invoiceUtil->generate($client['id'], $paymentUtil->getAmountByClientId($client['id']), $lastNumber, $invoiceDate);
        }
    }
}