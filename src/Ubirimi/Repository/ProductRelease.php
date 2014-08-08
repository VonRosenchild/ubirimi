<?php

namespace Ubirimi\Repository;

use Ubirimi\Container\UbirimiContainer;

class ProductRelease
{
    public static function addVersion($productId, $version, $date) {
        $query = "INSERT INTO sys_product_release(sys_product_id, version, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iss", $productId, $version, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public static function getLatestRelease($productId) {
        $query = "select * from sys_product_release where sys_product_id = ? order by date_created desc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            return $data;
        } else
            return null;
    }
}
