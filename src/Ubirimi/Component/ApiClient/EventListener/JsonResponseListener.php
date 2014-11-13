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

namespace Ubirimi\Component\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Component\ApiClient\ApiEvent;
use Ubirimi\Component\ApiClient\Event\ApiResponseEvent;

class JsonResponseListener implements EventSubscriberInterface
{
    public function onApiResponse(ApiResponseEvent $event)
    {
        $response = $event->getResponse();

        if ('application/json' != $response->getContentType()) {
            return;
        }

        if (!is_string($response->getContentType())) {
            return;
        }

        $response->setContent(json_decode($response->getContent(), true));
    }

    public static function getSubscribedEvents()
    {
        return array(
            ApiEvent::API_RESPONSE => 'onApiResponse'
        );
    }
}