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

    public static function getRepository($name) {
        $repository = null;
        if (array_key_exists($name, self::$repositories)) {
            $repository = self::$repositories[$name];
        } else {
            $classNameComponents = explode(".", $name);
            $className = 'Ubirimi\\' . ucfirst($classNameComponents[0]) .  '\Repository\\' . ucfirst($classNameComponents[1]) . '\\' . ucfirst($classNameComponents[2]);
            $repository = new $className;
            self::$repositories[$name] = $repository;
        }

        return $repository;
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