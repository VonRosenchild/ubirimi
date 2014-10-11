<?php

namespace Ubirimi\Container;

class UbirimiContainer
{
    private static $repositories = array();

    public static function get()
    {
        static $pimple;

        if (null == $pimple) {
            $pimple = new \Pimple();
        }

        return $pimple;
    }

    public static function setRepository($key, $repository) {
        self::$repositories[$key] = $repository;
    }

    public static function getRepositories() {
        return self::$repositories;
    }

    public static function register(ServiceProviderInterface $provider)
    {
        $provider->register(self::get());
        $provider->boot(self::get());
    }

    public static function loadConfigs($configs = array())
    {
        $pimple = self::get();

        foreach ($configs as $key => $value)
        {
            $pimple[$key] = $value;
        }
    }
}