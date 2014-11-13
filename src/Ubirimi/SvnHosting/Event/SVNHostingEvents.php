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

class SvnHostingEvents
{
    /**
     * SVN Hosting SVN Repository Event
     *
     * Event triggered when an action is performed upon a SVN repo.
     * The event will pass a Ubirimi\SvnHosting\Event\SvnHostingEvent Event.
     */
    const REPOSITORY = 'svn.repository';

    /**
     * SVN Password Update Event
     *
     * Event triggered when a user password is changed.
     * The Event will pass a Ubirimi\SvnHosting\Event\SvnHostingEvent Event.
     */
    const PASSWORD_UPDATE = 'svn.password_update';

    /**
     * SVN Import Users Event
     *
     * Event triggered when users from the system are imported into a repository.
     * The Event will pass a Ubirimi\SvnHosting\Event\SvnHostingEvent Event.
     */
    const IMPORT_USERS = 'svn.import_users';
}