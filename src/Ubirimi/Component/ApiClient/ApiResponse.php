<?php

namespace Ubirimi\Component\ApiClient;

class ApiResponse
{
    /**
     * Response Body
     *
     * @var mixed
     */
    private $content;

    /**
     * Http Status Code
     *
     * @var integer
     */
    private $statusCode;

    /**
     * Response Content Type
     *
     * @var string
     */
    private $contentType;

    public function __construct($content, $statusCode, $contentType)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}