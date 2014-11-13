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

class ConfigService
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