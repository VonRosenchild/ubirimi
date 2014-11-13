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

interface ClientInterface
{
    /**
     * Performs a GET request
     *
     * @param string $uri
     * @param array $params
     *
     * @return \Ubirimi\Component\ApiClient\ApiResponse
     */
    public function get($uri, $params = array());

    /**
     * Performs a POST requst
     *
     * @param string $uri
     * @param array $params
     * @return \Ubirimi\Component\ApiClient\ApiResponse
     */
    public function post($uri, $params = array());

    /**
     * Performs a PUT request
     *
     * @param string $uri
     * @param array $params
     * @return \Ubirimi\Component\ApiClient\ApiResponse
     */
    public function put($uri, $params = array());

    /**
     * Performs a DELETE request
     *
     * @param string $uri
     * @param array $params
     * @return \Ubirimi\Component\ApiClient\ApiResponse
     */
    public function delete($uri, $params = array());

    /**
     * Sets the base url for the calls
     *
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl);
}