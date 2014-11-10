<?php

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