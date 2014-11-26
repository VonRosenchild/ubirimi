<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Api\Controller\Client;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\PaymentUtil;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientId = $request->get('client_id');
        $clientData = $this->getRepository(UbirimiClient::class)->getById($clientId);
        $users = $this->getRepository(UbirimiClient::class)->getUsers($clientId, null, 'array');

        $paymentUtil = new PaymentUtil();

        $numberUsers = count($users);
        $amount = $paymentUtil->getAmountByUsersCount($numberUsers);
        $VAT = 0;
        if (in_array($clientData['sys_country_id'], array_keys(PaymentUtil::$VATValuePerCountry))) {
            $VAT = $amount * PaymentUtil::$VATValuePerCountry[$clientData['sys_country_id']] / 100;
        }

        return new JsonResponse([
            'amount' => $amount,
            'VAT' => $VAT,
        ]);
    }
}
