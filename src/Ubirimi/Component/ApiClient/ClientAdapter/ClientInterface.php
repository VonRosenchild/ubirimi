<?php

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