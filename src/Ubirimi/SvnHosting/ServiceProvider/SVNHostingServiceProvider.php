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

namespace Ubirimi\SvnHosting\Service;

use Ubirimi\Container\ServiceProviderInterface;

class SvnHostingServiceProvider implements ServiceProviderInterface
{
    public function register(\Pimple $pimple)
    {
        $pimple['svn.email'] = $pimple->share(function($pimple) {
            return new EmailService($pimple['session']);
        });
    }

    public function boot(\Pimple $pimple)
    {

    }
}