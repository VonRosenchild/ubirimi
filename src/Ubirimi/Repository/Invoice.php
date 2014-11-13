<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

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
    public function save($clientId, $amount, $number, $date)
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
    public function getLastInvoiceNumber()
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
    public function last12($clientId)
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
