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
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientData = $this->getRepository(UbirimiClient::class)->getById($request->get('id'));
        $users = $this->getRepository(UbirimiClient::class)->getUsers($request->get('id'), null, 'array');
        $administrators = $this->getRepository(UbirimiClient::class)->getAdministrators($request->get('id'), null, 'array');

        return new JsonResponse([
            'client' => $clientData,
            'users' => $users,
            'administrators' => $administrators
        ]);
    }
}
