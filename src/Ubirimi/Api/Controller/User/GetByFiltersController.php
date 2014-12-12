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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;

class GetByFiltersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $filters = json_decode($request->getContent(), true);
        $result = array();

        if ($filters['email_address'] && $filters['is_administrator']) {
            $result = $this->getRepository(UbirimiUser::class)->getByEmailAddressAndIsClientAdministrator(mb_strtolower($filters['email_address']));
        }

        if ($filters['username'] && $filters['domain']) {
            $result = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientDomain(mb_strtolower($filters['username']), mb_strtolower($filters['domain']));
        }

        return new JsonResponse($result);
    }
}
