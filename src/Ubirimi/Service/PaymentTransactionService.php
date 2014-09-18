<?php

use Ubirimi\Repository\Log;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Payment as PaymentRepository;
use Ubirimi\Repository\Invoice as InvoiceRepository;
use Ubirimi\Util;

class PaymentTransactionService
{
    private $paymillPrivateKey;

    public function __construct($paymillPrivateKey)
    {
        $this->paymillPrivateKey = $paymillPrivateKey;
    }

    /**
     * @param $token
     * @param $clientId
     * @param $clientContactEmail
     * @param $clientCompanyName
     * @return mixed
     * @throws Exception
     */
    public function execute($token, $clientId, $clientContactEmail, $clientCompanyName)
    {
        $service = new Paymill\Request($this->paymillPrivateKey);
        $client = new Paymill\Models\Request\Client();
        $payment = new Paymill\Models\Request\Payment();
        $transaction = new \Paymill\Models\Request\Transaction();

        $paymentUtil = new \Ubirimi\PaymentUtil();

        try {
            UbirimiContainer::get()['db.connection']->autocommit(false);

            $amount = $paymentUtil->getAmountByClientId($clientId);

            $client->setEmail($clientContactEmail);
            $client->setDescription($clientCompanyName);
            $clientResponse = $service->create($client);

            $payment->setToken($token);
            $payment->setClient($clientResponse->getId());
            $paymentResponse = $service->create($payment);

            $transaction->setPayment($paymentResponse->getId());
            $transaction->setAmount($amount * 100);
            $transaction->setCurrency('USD');
            $transaction->setDescription($clientCompanyName);
            $transactionResponse = $service->create($transaction);

            /* save a database record for the payment */
            $paymentDbRecord = PaymentRepository::save($clientId, $amount, 1);

            /* save a invoice database record for the payment */
            InvoiceRepository::save($paymentDbRecord['id'], InvoiceRepository::getLastInvoiceNumber() + 1, $paymentDbRecord['date_created']);

            UbirimiContainer::get()['db.connection']->commit();

        } catch (\Paymill\Services\PaymillException $e) {
            UbirimiContainer::get()['db.connection']->rollback();

            Log::add($clientId, \Ubirimi\SystemProduct::SYS_PRODUCT_YONGO, $clientId, sprintf('ERROR in payment [%s]', $e->getMessage()), Util::getServerCurrentDateTime());

            PaymentRepository::save($clientId, $amount, 0);

            throw new \Exception($e->getMessage());
        }

        return $paymentDbRecord;
    }
}