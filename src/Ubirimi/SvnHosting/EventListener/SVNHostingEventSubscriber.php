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

namespace Ubirimi\SvnHosting\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SvnHosting\Event\SvnHostingEvent;
use Ubirimi\SvnHosting\Event\SvnHostingEvents;

class SvnHostingEventSubscriber implements EventSubscriberInterface
{

    public function onSVN(SvnHostingEvent $event)
    {
        UbirimiContainer::get()['svn.email']->newUser(
            $event->getName(),
            $event->getUser()['firstName'],
            $event->getUser()['lastName'],
            $event->getUser()['lastName'],
            $event->getUser()['username'],
            $event->getUser()['mail']
        );
    }

    public function onPasswordUpdate(SvnHostingEvent $event)
    {
        UbirimiContainer::get()['svn.email']->passwordUpdate($event->getName(), $event->getUser(), $event->getExtra()['password']);
    }

    public function onImportUsers(SvnHostingEvent $event)
    {
        UbirimiContainer::get()['svn.email']->importUsers($event->getName(), $event->getUser());
    }

    public static function getSubscribedEvents()
    {
        return array(
            SvnHostingEvents::REPOSITORY => 'onSVN',
            SvnHostingEvents::PASSWORD_UPDATE => 'onPasswordUpdate',
            SvnHostingEvents::IMPORT_USERS => 'onImportUsers'
        );
    }
}