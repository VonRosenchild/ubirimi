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

namespace Ubirimi\Event;

class UbirimiEvents
{
    /**
     * Log Event
     *
     * Trigger this event when you want to log something.
     * The Event will pass a Ubirimi\Event\LogEvent Event.
     */
    const LOG = 'ubirimi.log';

    /**
     * User Event
     *
     * Event triggered when an operation is performed upon a user.
     * The Event will pass a Ubirimi\Event\UserEvent Event.
     */
    const USER = 'ubirimi.user';

    /**
     * Contact Message Event
     *
     * Event triggered when a contact message is sent via website.
     * The Event will pass the general purpose Ubirimi\Event\UbirimiEvent Event.
     */
    const CONTACT = 'ubirimi.contact';

    /**
     * Feedback Message Event
     *
     * Event triggered when a user sends us feedback.
     * The Event will pass the general purpose Ubirimi\Event\UbirimiEvent Event.
     */
    const FEEDBACK = 'ubirimi.feedback';

    /**
     * Password Recover Event
     *
     * Event triggered when user asks to recover the lost password.
     * The Event will pass the general purpose Ubirimi\Event\UbirimiEvent Event.
     */
    const PASSWORD_RECOVER = 'ubirimi.password_recover';
}