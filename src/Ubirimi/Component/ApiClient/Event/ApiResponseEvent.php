<?php

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