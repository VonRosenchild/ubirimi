<?php

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class Payment
{
    /**
     * Saves the payment and returns the newly created record
     *
     * @param $clientId
     * @param $amount
     * @param $successFlag
     * @return mixed
     */
    public function save($clientId, $amount, $successFlag)
    {
        $query = "INSERT INTO general_payment(client_id, amount, successful_flag, date_created) VALUES (?, ?, ?, ?)";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $createdDate = date('Y-m-d H:i:s');

        $stmt->bind_param("iiis", $clientId, $amount, $successFlag, $createdDate);
        $stmt->execute();


        /* return the payment record just created */
        $query = sprintf('SELECT *
                    FROM general_payment
                    WHERE id = %d
                    LIMIT 1', UbirimiContainer::get()['db.connection']->insert_id);

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $payment = $result->fetch_array(MYSQLI_ASSOC);

        return $payment;
    }

    /**
     * Gets the payment record for the given $clientId for the current month.
     * If no successful payment has been done returns null
     *
     * @param $clientId
     * @return mixed
     */
    public function getCurrentMonthPayment($clientId)
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        $query = 'SELECT *
                    FROM general_payment
                    WHERE client_id = ?
                      AND MONTH(date_created) = ?
                      AND YEAR(date_created) = ?
                      AND successful_flag = 1
                    LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $clientId, $currentMonth, $currentYear);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows) {
            $resultArray = array();
            while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                $resultArray[] = $user;
            }

            return $resultArray[0];
        }

        return null;
    }

    /**
     * Gets the payment record for the given $clientId for the previous month.
     * If no successful payment has been done returns null
     *
     * @param $clientId
     * @return mixed
     */
    public function getPreviousMonthPayment($clientId)
    {
        $currentMonth = date('n') - 1;
        $currentYear = date('Y');

        $query = 'SELECT *
                    FROM general_payment
                    WHERE client_id = ?
                      AND MONTH(date_created) = ?
                      AND YEAR(date_created) = ?
                      AND successful_flag = 1
                    LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $clientId, $currentMonth, $currentYear);

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows) {
            $resultArray = array();
            while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                $resultArray[] = $user;
            }

            return $resultArray[0];
        }

        return null;
    }
}
