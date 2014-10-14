<?php

    use Ubirimi\Util;

    Util::checkSuperUserIsLoggedIn();

    $clientId = $_POST['id'];

    $this->getRepository('ubirimi.general.client')->deleteById($clientId);