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

namespace Ubirimi\Component\ApiClient\Event;

use Symfony\Component\EventDispatcher\Event;
use Ubirimi\Component\ApiClient\ApiResponse;

/**
 * Api Response Event Class
 *
 * The Event holds the API response returned
 */
class ApiResponseEvent extends Event
{
    /**
     * @var \Ubirimi\Component\ApiClient\ApiResponse
     */
    private $response;

    /**
     * @param ApiResponse $response
     */
    public function __construct(ApiResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return \Ubirimi\Component\ApiClient\ApiResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}