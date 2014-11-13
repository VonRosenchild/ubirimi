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

namespace Ubirimi\Component\ApiClient\ClientAdapter;

use Guzzle\Http\ClientInterface as GuzzleClient;
use Guzzle\Http\Message\Request;
use Ubirimi\Component\ApiClient\ApiException;
use Ubirimi\Component\ApiClient\ApiResponse;
use Ubirimi\Component\ApiClient\ClientAdapter;

class GuzzleClientAdapter implements ClientInterface
{
    private $guzzle;

    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function request(Request $request)
    {
        try {
            $response = $request->send();
        }
        catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }

        $apiResponse = new ApiResponse(
            $response->getBody(),
            $response->getStatusCode(),
            $response->getContentType()
        );

        return $apiResponse;
    }

    public function get($uri, $params = array())
    {
        $request = $this->guzzle->get($uri, array(), $params);

        return $this->request($request);
    }

    public function post($uri, $params = array())
    {
        $request = $this->guzzle->post($uri, array(), json_encode($params));

        return $this->request($request);
    }

    public function delete($uri, $params = array())
    {

    }

    public function put($uri, $params = array())
    {

    }

    public function setBaseUrl($baseUrl)
    {
        $this->guzzle->setBaseUrl($baseUrl);
    }
}