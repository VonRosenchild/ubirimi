<?php

namespace Ubirimi\Service;

class Config
{
    private $configFile;

    public function __construct($path)
    {
        $this->configFile = $path;
    }

    public static function process($configFile)
    {
        $configs = parse_ini_file($configFile, null, INI_SCANNER_RAW);

        foreach ($configs as $key => $value) {
            if ("false" === $value) {
                $configs[$key] = false;
            } elseif ("true" === $value) {
                $configs[$key] = true;
            }
        }

        if (false === $configs) {
            throw new \Exception(sprintf('Invalid config file at [%s]', $configFile));
        }

        return $configs;
    }
}