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

namespace Ubirimi\ServiceProvider;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Ubirimi\Api\Service\BasicAuthenticationService;
use Ubirimi\Container\ServiceProviderInterface;
use Ubirimi\LoginTimeService\LoginTimeService;
use Ubirimi\Service\ClientService;
use Ubirimi\Service\DatabaseConnectorService;
use Ubirimi\Service\EmailService;
use Ubirimi\Service\LogService;
use Ubirimi\Service\PasswordService;
use Ubirimi\Service\RepositoryService;
use Ubirimi\Service\UserService;
use Ubirimi\Service\WarmUpService;

class UbirimiCoreServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple $pimple)
    {
        $pimple['db.connection'] = $pimple->share(function() {
            $databaseConnector = new DatabaseConnectorService();

            return $databaseConnector->getConnection();
        });

        $pimple['repository'] = $pimple->share(function() {
            return new RepositoryService();
        });

        $pimple['api.auth'] = $pimple->share(function($pimple) {
            $basicAuthenticationService = new BasicAuthenticationService();
            $basicAuthenticationService->setPasswordService($pimple['password']);

            return $basicAuthenticationService;
        });

        $pimple['password'] = $pimple->share(function() {
            return new PasswordService();
        });

        $pimple['dispatcher'] = $pimple->share(function() {
            return new EventDispatcher();
        });

        $pimple['log'] = $pimple->share(function($pimple) {
            return new LogService($pimple['session']);
        });

        $pimple['email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['session']);
        });

        $pimple['client'] = $pimple->share(function($pimple) {
            return new ClientService();
        });

        $pimple['user'] = $pimple->share(function($pimple) {
            return new UserService($pimple['session']);
        });

        $pimple['login.time'] = $pimple->share(function($pimple) {
            return new LoginTimeService();
        });

        $pimple['session'] = $pimple->share(function() {
            $lastDot = strrpos($_SERVER['SERVER_NAME'], '.');
            $secondToLastDot = strrpos($_SERVER['SERVER_NAME'], '.', $lastDot - strlen($_SERVER['SERVER_NAME']) - 1);

            $storage = new NativeSessionStorage(array('cookie_domain' => substr($_SERVER['SERVER_NAME'], $secondToLastDot)), new NativeFileSessionHandler());

            return new Session($storage, new NamespacedAttributeBag(), new AutoExpireFlashBag());
        });

        $pimple['warmup'] = $pimple->share(function($pimple) {
            return new WarmUpService($pimple['session']);
        });

        $pimple['savant'] = $pimple->share(function() {
            return new \Savant3(array(
                    'template_path' => array(
                        __DIR__ . '/../Yongo/Resources/views/email/',
                        __DIR__ . '/../General/Resources/views/email/',
                        __DIR__ . '/../Calendar/Resources/views/email/',
                        __DIR__ . '/../SvnHosting/Resources/views/email/',
                        __DIR__ . '/../Resources/views/'
                    ))
            );
        });
    }

    public function boot(\Pimple $pimple)
    {

    }
}
