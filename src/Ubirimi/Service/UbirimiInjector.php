<?php

namespace Ubirimi\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Ubirimi\Component\ApiClient\ApiClient;
use Ubirimi\Component\ApiClient\ClientAdapter\GuzzleClientAdapter as GuzzleAdapter;
use Ubirimi\Container\ServiceInjectorInterface;

class UbirimiInjector implements ServiceInjectorInterface
{
    public static function inject(\Pimple $pimple)
    {
        $pimple['dispatcher'] = $pimple->share(function() {
            return new EventDispatcher();
        });

        $apiClient = new ApiClient(new GuzzleAdapter(new \Guzzle\Http\Client()), $pimple['api.base']);
        $apiClient->setEventDispatcher($pimple['dispatcher']);

        $databaseConnector = new DatabaseConnector();

        $pimple['log'] = $pimple->share(function($pimple) {
            return new Log($pimple['api.client'], $pimple['session']);
        });

        $pimple['email'] = $pimple->share(function($pimple) {
            return new Email($pimple['api.client'], $pimple['session']);
        });

        $pimple['api.client'] = $pimple->share(function() use ($apiClient) {
            return $apiClient;
        });

        $pimple['user'] = $pimple->share(function($pimple) {
            return new User($pimple['api.client'], $pimple['session']);
        });

        $pimple['session'] = $pimple->share(function() {
            $lastDot = strrpos($_SERVER['SERVER_NAME'], '.');
            $secondToLastDot = strrpos($_SERVER['SERVER_NAME'], '.', $lastDot - strlen($_SERVER['SERVER_NAME']) - 1);

            $storage = new NativeSessionStorage(array('cookie_domain' => substr($_SERVER['SERVER_NAME'], $secondToLastDot)), new NativeFileSessionHandler());

            return new Session($storage, new NamespacedAttributeBag(), new AutoExpireFlashBag());
        });

        $pimple['warmup'] = $pimple->share(function($pimple) {
            return new WarmUp($pimple['session']);
        });

        $pimple['savant'] = $pimple->share(function() {
            return new \Savant3(array(
                'template_path' => array(
                        __DIR__ . '/../Yongo/Resources/views/email/',
                        __DIR__ . '/../General/Resources/views/email/',
                        __DIR__ . '/../Calendar/Resources/views/email/',
                        __DIR__ . '/../SVNHosting/Resources/views/email/',
                        __DIR__ . '/../FrontendCOM/Resources/views/email/',
                        __DIR__ . '/../Resources/views/'
                ))
            );
        });

        $pimple['db.connection'] = $pimple->share(function() use ($databaseConnector) {
            return $databaseConnector->getConnection();
        });

        $pimple['payment.transaction'] = $pimple->share(function($pimple) {
            return new \PaymentTransaction($pimple['paymill.private_key']);
        });
    }
}