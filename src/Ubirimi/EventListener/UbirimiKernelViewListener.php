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

namespace Ubirimi\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Ubirimi\Container\UbirimiContainer;

class UbirimiKernelViewListener
{
    public function onKernelView(GetResponseForControllerResultEvent $getControllerResponseEvent)
    {
        $controllerResponse = $getControllerResponseEvent->getControllerResult();

        $view = $controllerResponse[0];
        $variables = $controllerResponse[1];

        ob_start();

        extract($variables);

        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');
        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $session = UbirimiContainer::get()['session'];

        require_once $view;

        $response = ob_get_clean();

        $getControllerResponseEvent->setResponse(new Response($response));
    }
}