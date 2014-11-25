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

namespace Ubirimi\Api\Controller\User;

use Sabre\VObject\Parser\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;

class AuthenticateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $requestParameters = json_decode($request->getContent(), true);

        $userData = $this->getRepository(UbirimiUser::class)->getByUsernameAndAdministrator($requestParameters['username']);

        $response = array('success' => 0);

        if ($userData['id']) {
            if (UbirimiContainer::get()['password']->check($requestParameters['password'], $userData['password'])) {

                UbirimiContainer::get()['warmup']->warmUpClient($userData);
                UbirimiContainer::get()['login.time']->clientSaveLoginTime($userData['client_id']);

                $clientData = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getById($userData['client_id']);
                $clientSettings = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getSettings($userData['client_id']);

                $response['success'] = 1;
                $response['user'] = $userData;
                $response['client'] = $clientData;
                $response['client_settings'] = $clientSettings;
            }
        }

        return new JsonResponse($response);
    }
}