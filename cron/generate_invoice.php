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

$currentDate = date('Y-m-d');
while ($client = $clients->fetch_array(MYSQLI_ASSOC)) {
    $lastInvoice = $invoiceUtil->getLastByClientIdAndMonth($client['id'], date('m'));
    if (!$lastInvoice) {
        $lastInvoice = $invoiceUtil->getLast();
        $lastNumber = $lastInvoice['number'];
        $lastNumber++;
        $invoiceUtil->generate($client['id'], $paymentUtil->getAmountByClientId($client['id']), $currentDate, $lastNumber);
    }
}