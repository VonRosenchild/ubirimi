<?php

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;

class BugzillaConnectorService
{
    public function getConnection() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $connection = new \mysqli(
            UbirimiContainer::get()['bugzilla.database.host'],
            UbirimiContainer::get()['bugzilla.database.user'],
            UbirimiContainer::get()['bugzilla.database.password'],
            UbirimiContainer::get()['bugzilla.database.name']);

        if ($connection->connect_errno) {
            throw new \Exception(sprintf('Failed to connect to database (%d). Reason: %s', $connection->connect_errno, $connection->connect_error));
        }

        $query = "SET NAMES 'utf8'";

        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
        }

        return $connection;
    }
}