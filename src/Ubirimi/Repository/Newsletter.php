<?php

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class Newsletter
{
    public static function addSubscription($emailAddress, $currentDate) {
        $query = "INSERT INTO newsletter(email_address, date_created) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $emailAddress, $currentDate);
        $stmt->execute();
    }

    public static function checkEmailAddressDuplication($emailAddress) {
        $query = 'select id from newsletter where LOWER(email_address) = LOWER(?) limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $emailAddress);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array();
    }
}
