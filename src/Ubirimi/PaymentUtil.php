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
    public static $VATValuePerCountry = array(143 => 24, // Romania
                                       10 => 20,   // Austria
                                       17 => 21,   // Belgium
                                       17 => 20,   // Bulgaria
                                       17 => 19,   // Cyprus
                                       17 => 21,   // Check republic
                                       17 => 25,   // Croatia
                                       17 => 25,   // Denmark
                                       17 => 20,   // Estonia
                                       17 => 24,   // Finland,
                                       17 => 20,   // France
                                       17 => 19,   // Germany
                                       17 => 23,   // Greece
                                       17 => 27,   // Hungary
                                       17 => 23,   // Ireland
                                       17 => 22,   // Italy
                                       17 => 21,   // latvia
                                       17 => 21,   // Lithuania
                                       17 => 15,   // Luxembourg
                                       17 => 18,   // Malta
                                       17 => 21,   // Netherlands
                                       17 => 23,   // Poland
                                       17 => 23,   // Portugal
                                       17 => 20,   // Slovakia
                                       17 => 22,   // Slovenia
                                       17 => 21,   // Spain
                                       17 => 21,   // Canary Islands
                                       17 => 25,   // Sweden
                                       17 => 20    // United Kingdom
                                       );

    public function getAmountByUsersCount($usersCount) {
        switch ($usersCount) {
            case $usersCount <= 10:
                $amount = 10;
                break;
            case $usersCount <= 15;
                $amount = 45;
                break;
            case $usersCount <= 25;
                $amount = 90;
                break;
            case $usersCount <= 50;
                $amount = 190;
                break;
            case $usersCount <= 100;
                $amount = 290;
                break;
            case $usersCount <= 500;
                $amount = 490;
                break;
            case $usersCount <= 1000;
                $amount = 990;
                break;
        }

        return $amount;
    }

    public function getAmountByClientId($clientId)
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