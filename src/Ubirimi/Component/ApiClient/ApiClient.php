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

namespace Ubirimi\Component\ApiClient;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Ubirimi\Component\ApiClient\ClientAdapter\ClientInterface;
use Ubirimi\Component\ApiClient\Event\ApiResponseEvent;

class ApiClient
{
    /**
     * Api Client Adapter
     *
     * @var \Ubirimi\Component\ApiClient\ClientAdapter\ClientInterface
     */
    private $adapter;

    /**
     * Api base URL
     *
     * @var string
     */
    private $baseUrl;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(ClientInterface $client, $baseUrl = null)
    {
        $this->adapter = $client;
        if (null !== $baseUrl) {
            $this->baseUrl = $baseUrl;
            $this->adapter->setBaseUrl($baseUrl);
        }
    }

    public function handleResponse(ApiResponse $response)
    {
        if (null != $this->eventDispatcher) {
            $event = new ApiResponseEvent($response);

            $this->eventDispatcher->dispatch(ApiEvent::API_RESPONSE, $event);
        }

        return $response;
    }

    public function get($uri, $params = array())
    {
        $response = $this->adapter->get($uri, $params);

        return $this->handleResponse($response);
    }

    public function post($uri, $params = array())
    {
        $response = $this->adapter->post($uri, $params);

        return $this->handleResponse($response);
    }

    public function setEventDispatcher(EventDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function prepareAndExecuteGet($uri, $uriParams = array(), $params = array())
    {
        $uri = $uri . '?' . $this->prepareUriParams($uriParams);

        $response = $this->adapter->get($uri, $params);

        return $this->handleResponse($response);
    }

    private function prepareUriParams($uriParams = array())
    {
        $query = '';

        $keyValuePair = function ($value, $key) use (&$query) {
            $query .= $key . '=' . $value . '&';

            return $query;
        };

        array_walk($uriParams, $keyValuePair);

        return substr($query, 0, -1);
    }
}