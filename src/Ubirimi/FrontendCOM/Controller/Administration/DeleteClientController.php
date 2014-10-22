<?php

    use Ubirimi\Util;

    Util::checkSuperUserIsLoggedIn();

    $clientId = $request->request->get('id');

    $this->getRepository('ubirimi.general.client')->deleteById($clientId);