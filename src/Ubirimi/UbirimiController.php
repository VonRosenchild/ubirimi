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

namespace Ubirimi;

use Ubirimi\Container\UbirimiContainer;

class UbirimiController
{

    public function getLogger() {
        return UbirimiContainer::get()['logger'];
    }

    public function getRepository($name)
    {
        return UbirimiContainer::get()['repository']->get($name);
    }

    public function render($path, $variables)
    {
        return array($path, $variables);
    }

    public function getLoggerContext() {

        return array('client_id' => UbirimiContainer::get()['session']->get('client/id'),
                     'user_id' => UbirimiContainer::get()['session']->get('user/id'));
    }
}