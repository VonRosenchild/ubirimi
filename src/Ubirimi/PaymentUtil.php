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
    public static $VATValuePerCountry = array(
        143 => 24,  // Romania
        10 => 20,   // Austria
        17 => 21,   // Belgium
        26 => 20,   // Bulgaria
        44 => 19,   // Cyprus
        45 => 21,   // Check republic
        42 => 25,   // Croatia
        46 => 25,   // Denmark
        56 => 20,   // Estonia
        59 => 24,   // Finland,
        60 => 20,   // France
        64 => 19,   // Germany
        66 => 23,   // Greece
        74 => 27,   // Hungary
        80 => 23,   // Ireland
        82 => 22,   // Italy
        96 => 21,   // latvia
        102 => 21,   // Lithuania
        103 => 15,   // Luxembourg
        110 => 18,   // Malta
        126 => 21,   // Netherlands
        140 => 23,   // Poland
        141 => 23,   // Portugal
        158 => 20,   // Slovakia
        159 => 22,   // Slovenia
        164 => 21,   // Spain
        169 => 25,   // Sweden
        186 => 20   // United Kingdom
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
        $users = count($this->getRepository('ubirimi.general.client')->getUsers($clientId, null, 'array'));

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