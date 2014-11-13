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

namespace Ubirimi\Service;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Ubirimi\Container\UbirimiContainer;

class RouteBootstrapService
{
    /**
     * @var \Symfony\Component\Routing\RequestContext;
     */
    private $context;

    /**
     * @var \Symfony\Component\Routing\Router;
     */
    private $router;

    /**
     * @var \Symfony\Component\Routing\RouteCollection;
     */
    private $ubirimiRouteCollection;

    /**
     * @return \Symfony\Component\Routing\Matcher\UrlMatcherInterface
     */
    public function bootstrap($cache, $onDemand = true)
    {
        $this->context = new RequestContext();
        $this->context->fromRequest(Request::createFromGlobals());

        if (false === $cache) {
            $this->processRoutes($onDemand);

            return $this->router->getMatcher();
        } else {
            if (!file_exists(UbirimiContainer::get()['app.cacheDir'] . '/ProjectUrlMatcher.php')) {
                $this->processRoutes($onDemand);

                return $this->router->getMatcher();
            }

            require_once UbirimiContainer::get()['app.cacheDir'] . '/ProjectUrlMatcher.php';

            return new \ProjectUrlMatcher($this->context);
        }
    }

    /**
     * @return RequestContext
     */
    public function getRequestContext()
    {
        return $this->context;
    }

    private function processRoutes($onDemand)
    {
        $this->context = new RequestContext();
        $this->context->fromRequest(Request::createFromGlobals());

        /* delete previous route cache if exists */
        @unlink(UbirimiContainer::get()['app.cacheDir'] . '/ProjectUrlMatcher.php');

        /* these are the routing yaml files that are loaded regardless of deployment context (onDemand, download) */
        $routingPaths = array(
            __DIR__ . '/../Yongo/Resources/config',
            __DIR__ . '/../Documentador/Resources/config',
            __DIR__ . '/../SvnHosting/Resources/config',
            __DIR__ . '/../Agile/Resources/config',
            __DIR__ . '/../HelpDesk/Resources/config',
            __DIR__ . '/../Calendar/Resources/config',
            __DIR__ . '/../GeneralSettings/Resources/config',
            __DIR__ . '/../QuickNotes/Resources/config',
            __DIR__ . '/../Api/Resources/config'
        );

        /* for these two projects, the administration routes are in routing_administration files, so load them separately */
        $routingPathsAdministration = array(
            __DIR__ . '/../Yongo/Resources/config',
            __DIR__ . '/../SvnHosting/Resources/config'
        );

        $options = array('cache_dir' => UbirimiContainer::get()['app.cacheDir']);

        $this->router = new Router(
            new YamlFileLoader(new FileLocator(__DIR__ . '/../Yongo/Resources/config')),
            'routing.yml',
            $options,
            $this->context
        );

        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/../Frontend/Resources/config'));
        $this->router->getRouteCollection()->addCollection($routeCollection = $loader->load('routing.yml'));

        foreach ($routingPaths as $routingPath) {
            $loader = new YamlFileLoader(new FileLocator($routingPath));

            $this->router->getRouteCollection()->addCollection($routeCollection = $loader->load('routing.yml'));
        }

        foreach ($routingPathsAdministration as $routingPath) {
            $loader = new YamlFileLoader(new FileLocator($routingPath));

            $this->router->getRouteCollection()->addCollection($routeCollection = $loader->load('routing_administration.yml'));
        }
    }
}