<?php

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class Invoice
{
    /**
     * Save a invoice record with the paymentId, number and date of payment.
     *
     * @param $paymentId
     * @param $number
     * @param $date
     * @return mixed
     */
    public static function save($paymentId, $number, $date)
    {
        $query = "INSERT INTO general_invoice(general_payment_id, number, date_created) VALUES (?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $paymentId, $number, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    /**
     * Returns the last invoice number. Increment this by 1 to get the next invoice number.
     *
     * @return int
     */
    public static function getLastInvoiceNumber()
    {
        $query = 'SELECT number
                        FROM general_invoice
                        ORDER BY number DESC
                        LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $resultArray = array();
        while ($invoice = $result->fetch_array(MYSQLI_ASSOC)) {
            $resultArray[] = $invoice;
        }

        if (empty($resultArray)) {
            return 0;
        }

        return $resultArray[0]['number'];
    }

    /**
     * Get client's last 12 invoices together with payment information.
     *
     * @param $clientId
     * @return array
     */
    public static function last12($clientId)
    {
        $query = 'SELECT gi.*, gp.*
                    FROM general_invoice gi
                    LEFT JOIN general_payment gp ON gi.general_payment_id = gp.id
                    WHERE gp.client_id = ?
                      AND successful_flag = 1
                  LIMIT 12';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);

        $stmt->execute();

        $result = $stmt->get_result();
        $resultArray = array();
        while ($invoice = $result->fetch_array(MYSQLI_ASSOC)) {
            $resultArray[] = $invoice;
        }

        return $resultArray;
    }

    /**
     * Get invoice and payment data for the given paymentId
     *
     * @param $paymentId
     */
    public static function getByPaymentId($paymentId)
    {
        $query = 'SELECT general_invoice.id, general_invoice.number, general_invoice.date_created, general_payment.amount,
                    general_payment.client_id
                    FROM general_invoice
                    LEFT JOIN general_payment ON general_payment.id = general_invoice.general_payment_id
                    WHERE general_invoice.general_payment_id = ?
                  LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $paymentId);
        $stmt->execute();
        $result = $stmt->get_result();

        $invoice = $result->fetch_array(MYSQLI_ASSOC);

        return $invoice;
    }
}
