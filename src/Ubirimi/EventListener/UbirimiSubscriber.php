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

namespace Ubirimi\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Event\UserEvent;

class UbirimiSubscriber implements EventSubscriberInterface
{
    public function onUserCustomer(UserEvent $event) {
        if (UserEvent::STATUS_NEW == $event->getStatus()) {

        }
    }

    public function onUser(UserEvent $event)
    {
        switch ($event->getStatus())
        {
            case UserEvent::STATUS_NEW:
                switch ($event->getExtra()['isCustomer']) {
                    case true:
                        UbirimiContainer::get()['email']->newUserCustomer(
                            $event->getFirstName(),
                            $event->getLastName(),
                            $event->getPassword(),
                            $event->getEmail(),
                            $event->getExtra()['clientDomain'],
                            $event->getExtra()['clientId']);
                        break;

                    case false;
                        UbirimiContainer::get()['email']->newUser(
                            $event->getFirstName(),
                            $event->getLastName(),
                            $event->getUsername(),
                            $event->getPassword(),
                            $event->getEmail(),
                            $event->getExtra()['clientDomain'],
                            $event->getExtra()['clientId']);
                        break;
                }
                break;

            case UserEvent::STATUS_NEW_SVN:
                UbirimiContainer::get()['svn.email']->newUser(
                    $event->getExtra()['repositoryName'],
                    $event->getFirstName(),
                    $event->getLastName(),
                    $event->getUsername(),
                    $event->getEmail()
                );
                break;
        }
    }

    public function onFeedback(UbirimiEvent $event)
    {
        UbirimiContainer::get()['email']->feedback(
            $event->getData()['userData'],
            $event->getData()['like'],
            $event->getData()['improve'],
            $event->getData()['newFeatures'],
            $event->getData()['experience']);
    }

    public function onPasswordRecover(UbirimiEvent $event)
    {
        UbirimiContainer::get()['email']->passwordRecover($event->getData()['email'], $event->getData()['password']);
    }

    public static function getSubscribedEvents()
    {
        return array(
            UbirimiEvents::USER => 'onUser',
            UbirimiEvents::FEEDBACK => 'onFeedback',
            UbirimiEvents::PASSWORD_RECOVER => 'onPasswordRecover');
    }
}
