<?php

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