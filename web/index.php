<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\EventListener\UbirimiKernelViewListener;
use Ubirimi\UbirimiControllerResolver;

require_once __DIR__ . '/bootstrap.php';

/**
 * init session and expose some global variables that are used everywhere
 * these variables would be later injected into the controller
 *
 * - $session - the session abstraction itself
 * - $clientId
 * - $loggedInUserId
 */
$session = UbirimiContainer::get()['session'];
$session->start();

$clientId = $session->has('client/id') ? $session->get('client/id') : null;
$loggedInUserId = $session->has('user/id') ? $session->get('user/id'): null;

if ($clientId) {
    date_default_timezone_set($session->get('client/settings/timezone'));
} else {
    date_default_timezone_set('Europe/London');
}

try {
    $request = Request::createFromGlobals();
    $request->setSession($session);

    UbirimiContainer::get()['dispatcher']->addSubscriber(new RouterListener($urlMatcher));
    UbirimiContainer::get()['dispatcher']->addListener(KernelEvents::VIEW, array(new UbirimiKernelViewListener(), 'onKernelView'));

    $resolver = new UbirimiControllerResolver();
    $kernel = new HttpKernel(UbirimiContainer::get()['dispatcher'], $resolver);

    $response = $kernel->handle($request);

    $response->send();

    $kernel->terminate($request, $response);
} catch (\InvalidArgumentException $e) {
    try {
        $routeMatchedParams = $urlMatcher->match($routeBootstrapper->getRequestContext()->getPathInfo());

        /* inject GET with route params */
        array_walk($routeMatchedParams, function($value, $key) {
            $_GET[$key] = $value;
        });

        require_once __DIR__ . '/../src/' . str_replace('\\', '/', $routeMatchedParams['_controller']) . '.php';

    } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {

        require_once __DIR__ . '/notFound.html';
    } catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
        require_once __DIR__ . '/notFound.html';
    }
}