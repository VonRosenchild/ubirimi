<?php

namespace Ubirimi\HelpDesk\Repository\Organization;

use Ubirimi\Container\UbirimiContainer;

class Customer
{
    public static function getByOrganizationId($organizationId) {
        $query = 'SELECT user.id, user.first_name, user.last_name, user.email ' .
                 'from help_customer ' .
                 'left join user on user.id = help_customer.user_id ' .
                 'where help_organization_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $organizationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function getByName($clientId, $name, $organizationId = null) {
        $query = 'select id, name, description ' .
            'from help_organization ' .
            'where client_id = ? ' .
            'and LOWER(name) = ? ';

        if ($organizationId)
            $query .= 'and id != ?';

        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($organizationId)
            $stmt->bind_param("isi", $clientId, $name, $organizationId);
        else
            $stmt->bind_param("is", $clientId, $name);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public static function create($organizationId, $userId) {
        $query = "INSERT INTO help_customer(help_organization_id, user_id) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $organizationId, $userId);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }
}
