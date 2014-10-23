<?php

namespace Ubirimi\SVNHosting\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SVNHosting\Event\SVNHostingEvent;
use Ubirimi\SVNHosting\Event\SVNHostingEvents;

class SVNHostingEventSubscriber implements EventSubscriberInterface
{

    public function onSVN(SVNHostingEvent $event)
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

    public function onPasswordUpdate(SVNHostingEvent $event)
    {
        UbirimiContainer::get()['svn.email']->passwordUpdate($event->getName(), $event->getUser(), $event->getExtra()['password']);
    }

    public function onImportUsers(SVNHostingEvent $event)
    {
        UbirimiContainer::get()['svn.email']->importUsers($event->getName(), $event->getUser());
    }

    public static function getSubscribedEvents()
    {
        return array(
            SVNHostingEvents::REPOSITORY => 'onSVN',
            SVNHostingEvents::PASSWORD_UPDATE => 'onPasswordUpdate',
            SVNHostingEvents::IMPORT_USERS => 'onImportUsers'
        );
    }
}