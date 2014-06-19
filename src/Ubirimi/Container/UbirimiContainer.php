<?php

namespace Ubirimi\Container;

class UbirimiContainer
{
    public static function get()
    {
        static $pimple;

        if (null == $pimple) {
            $pimple = new \Pimple();
        }

        return $pimple;
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