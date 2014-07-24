<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    Util::checkSuperUserIsLoggedIn();

    $clientId = $_POST['id'];

    Client::deleteById($clientId);