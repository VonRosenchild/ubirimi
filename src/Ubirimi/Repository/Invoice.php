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
    public static function save($clientId, $amount, $number, $date)
    {
        $query = "INSERT INTO general_invoice(client_id, amount, number, date_created) VALUES (?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $clientId, $amount, $number, $date);
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
        $query = 'SELECT gi.*
                    FROM general_invoice gi
                    WHERE gi.client_id = ?
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
}
