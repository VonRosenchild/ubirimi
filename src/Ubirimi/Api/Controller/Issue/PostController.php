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

namespace Ubirimi\Api\Controller\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;


class PostController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $timeTrackingDefaultUnit = $this->getRepository(UbirimiClient::class)->getYongoSetting(
            $request->get('api_client_id'),
            'time_tracking_default_unit'
        );

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($request->get('api_client_id'));

        $issue = UbirimiContainer::get()['issue']->save(
            array('id' => $request->get('projectId')),
            array(
                'resolution' => $request->get('resolution'),
                'priority' => $request->get('priority'),
                'type' => $request->get('type'),
                'assignee' => $request->get('assignee'),
                'summary' => $request->get('summary'),
                'description' => $request->get('description'),
                'environment' => $request->get('environment'),
                'reporter' => $request->get('api_user_id'),
                'due_date' => $request->get('due_date')
            ),
            null,
            $timeTrackingDefaultUnit,
            $request->get('projectId'),
            array(),
            array(),
            $clientSettings,
            $request->get('api_user_id'),
            $request->get('api_client_id')
        );

        return new JsonResponse($issue);
    }
}
