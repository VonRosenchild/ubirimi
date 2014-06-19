<?php

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