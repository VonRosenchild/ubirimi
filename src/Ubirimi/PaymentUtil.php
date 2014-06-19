<?php

namespace Ubirimi;

use Ubirimi\Repository\Client;
use Ubirimi\Container\UbirimiContainer;

class PaymentUtil
{
    /**
     * Get the value of the license (monthly subscription) based on the number of users for onDemand scenarios
     * Also applies the discount in license.discount
     *
     * @param $clientId
     * @return int the value of the monthly subscription
     */
    public function getAmount($clientId)
    {
        $amount = 0;
        $users = count(Client::getUsers($clientId, null, 'array'));

        switch ($users) {
            case $users <= 10:
                $amount = 10;
                break;
            case $users <= 15;
                $amount = 45;
                break;
            case $users <= 25;
                $amount = 90;
                break;
            case $users <= 50;
                $amount = 190;
                break;
            case $users <= 100;
                $amount = 290;
                break;
            case $users <= 500;
                $amount = 490;
                break;
            case $users <= 1000;
                $amount = 990;
                break;
        }

        return (int) ($amount - UbirimiContainer::get()['license.discount'] * $amount / 100);
    }
}