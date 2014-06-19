<?php

namespace Ubirimi\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Ubirimi\Container\UbirimiContainer;

class UbirimiKernelViewListener
{
    public function onKernelView(GetResponseForControllerResultEvent $getControllerResponseEvent)
    {
        $controllerResponse = $getControllerResponseEvent->getControllerResult();

        $view = $controllerResponse[0];
        $variables = $controllerResponse[1];

        ob_start();

        extract($variables);

        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');
        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $session = UbirimiContainer::get()['session'];

        require_once $view;

        $response = ob_get_clean();

        $getControllerResponseEvent->setResponse(new Response($response));
    }
}