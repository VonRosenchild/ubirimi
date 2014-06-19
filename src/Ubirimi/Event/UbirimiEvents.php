<?php

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