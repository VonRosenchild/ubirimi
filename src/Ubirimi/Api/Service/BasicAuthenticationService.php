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

namespace Ubirimi\Api\Service;

use Symfony\Component\HttpFoundation\Request;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Service\PasswordService;

class BasicAuthenticationService
{
    /**
     * @var \Ubirimi\Service\PasswordService;
     */
    private $passwordService;

    public function setPasswordService(PasswordService $service)
    {
        $this->passwordService = $service;
    }

    public function auth(Request $request)
    {
        $decodedHeader = base64_decode(str_replace('Basic ', '', $request->headers->get('Authorization')));

        list($clientDomain, $username) = explode('#', substr($decodedHeader, 0, strpos($decodedHeader, ':')));
        $password = substr($decodedHeader, strpos($decodedHeader, ':') + 1);

        $user = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientDomain($username, $clientDomain);

        if (null === $user) {
            throw new \Exception(sprintf('Api Auth Failed. User [%s] not found', $username));
        }

        if (false === $this->passwordService->check($password, $user['password'])) {
            throw new \Exception(sprintf('Api Auth Failed. Wrong password for user [%s]', $username));
        }

        $request->attributes->set('api_client_id', $user['client_id']);
        $request->attributes->set('api_client_domain', $clientDomain);
        $request->attributes->set('api_username', $username);
        $request->attributes->set('api_user_id', $user['id']);
    }
}
