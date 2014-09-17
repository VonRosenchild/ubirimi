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
                                       10 => 20,  // Austria
                                       17 => 21,   // Belgium
                                       17 => 21,   // Bulgaria
                                       17 => 21,   // Cyprus
                                       17 => 21,   // Cehia
                                       17 => 21,   // Croatia
                                       17 => 21,   // Danemarca
                                       17 => 21,   // Estonia
                                       17 => 21,   // Finland
                                       17 => 21,   // germany
                                       17 => 21,   // Greece
                                       17 => 21,   // Hungary
                                       17 => 21,   // Ireland
                                       17 => 21,   // Italy
                                       17 => 21,   // latvia
                                       17 => 21,   // Lithuania
                                       17 => 21,   // Luxembourg
                                       17 => 21,   // Malta
                                       17 => 21,   // Netherlands
                                       17 => 21,   // Poland
                                       17 => 21,   // Portugal
                                       17 => 21,   // Madeira
                                       17 => 21,   // Azores
                                       17 => 21,   // Slovakia
                                       17 => 21,   // Slovenia
                                       17 => 21,   // Spain
                                       17 => 21,   // Canary Islands
                                       17 => 21,   // Sweden
                                       17 => 21,   // United Kingdom
                                       17 => 21    // Isle of man
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