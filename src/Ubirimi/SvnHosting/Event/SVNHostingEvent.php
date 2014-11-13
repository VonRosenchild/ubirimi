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

namespace Ubirimi\SvnHosting\Event;

use Symfony\Component\EventDispatcher\Event;

class SvnHostingEvent extends Event
{
    /**
     * Slugged name of SVN repo
     *
     * @var string
     */
    private $name;

    /**
     * The user that performed the action
     *
     * @var array
     */
    private $user;

    /**
     * Extra data passed to the event
     *
     * @var array
     */
    private $extra;

    public function __construct($name = null, $user = null, $extra = array())
    {
        $this->name = $name;
        $this->user = $user;
        $this->extra = $extra;
    }

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user
     *
     * @return array|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get extra data
     *
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }
}