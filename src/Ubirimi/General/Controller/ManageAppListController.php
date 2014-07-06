<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $application = $_POST['app'];
    $visible = $_POST['visible'];

    $currentDate = Util::getServerCurrentDateTime();

    switch ($application) {
        case 'yongo':
            $productId = SystemProduct::SYS_PRODUCT_YONGO;
            break;
        case 'agile':
            $productId = SystemProduct::SYS_PRODUCT_CHEETAH;
            break;
        case 'helpdesk':
            $productId = SystemProduct::SYS_PRODUCT_HELP_DESK;
            break;
        case 'events':
            $productId = SystemProduct::SYS_PRODUCT_CALENDAR;
            break;
        case 'documentador':
            $productId = SystemProduct::SYS_PRODUCT_DOCUMENTADOR;
            break;
        case 'svn':
            $productId = SystemProduct::SYS_PRODUCT_SVN_HOSTING;
            break;
    }

    if ($visible) {
        Client::addProduct($clientId, $productId, $currentDate);
    } else {
        Client::deleteProduct($clientId, $productId);
        if ($productId == SystemProduct::SYS_PRODUCT_YONGO) {
            Client::deleteProduct($clientId, SystemProduct::SYS_PRODUCT_HELP_DESK);
            Client::deleteProduct($clientId, SystemProduct::SYS_PRODUCT_CHEETAH);
        }
    }

    $clientProducts = Client::getProducts($clientId, 'array');
    UbirimiContainer::get()['session']->remove("client/products");
    if (count($clientProducts)) {
        array_walk($clientProducts, function($value, $key) {
            UbirimiContainer::get()['session']->set("client/products/{$key}", $value);
        });
    } else {
        Client::addProduct($clientId, $productId, $currentDate);
        $session->set('client/products', array(array('sys_product_id' => $productId)));
    }
