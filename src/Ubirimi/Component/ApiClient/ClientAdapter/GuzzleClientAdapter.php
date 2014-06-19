<?php

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