<?php

use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Util;

Util::checkSuperUserIsLoggedIn();

    $clientId = $request->request->get('id');

    $this->getRepository(UbirimiClient::class)->deleteById($clientId);