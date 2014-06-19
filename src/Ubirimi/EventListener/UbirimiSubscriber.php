<?php

namespace Ubirimi\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Event\UserEvent;

class UbirimiSubscriber implements EventSubscriberInterface
{
    public function onLog(LogEvent $event)
    {
        UbirimiContainer::get()['log']->log($event->getProductId(), $event->getMessage());
    }

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

    public function onContact(UbirimiEvent $event)
    {
        UbirimiContainer::get()['email']->contact(
            $event->getData()['name'],
            $event->getData()['category'],
            $event->getData()['message'],
            $event->getData()['email']);
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
            UbirimiEvents::LOG => 'onLog',
            UbirimiEvents::USER => 'onUser',
            UbirimiEvents::CONTACT => 'onContact',
            UbirimiEvents::FEEDBACK => 'onFeedback',
            UbirimiEvents::PASSWORD_RECOVER => 'onPasswordRecover');
    }
}