<?php

namespace Ubirimi\Yongo\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;

class IssueEventSubscriber implements EventSubscriberInterface
{

    public function onIssue(IssueEvent $event)
    {
        $container = UbirimiContainer::get();
    }

    public function onIssueEmail(IssueEvent $event)
    {
        $container = UbirimiContainer::get();

        switch ($event->getStatus())
        {
            case IssueEvent::STATUS_NEW:
                $container['issue.email']->emailIssueNew($event->getIssue());
                break;
            case IssueEvent::STATUS_DELETE:
                $container['issue.email']->emailIssueDelete($event->getIssue(), $event->getProject(), $event->getExtra());
                break;
            case IssueEvent::STATUS_UPDATE:
                $container['issue.email']->emailIssueUpdate($event->getIssue(), $event->getExtra()['oldIssueData'], $event->getExtra()['fieldChanges']);
                break;
        }
    }

    public function onIssueCommentEmail(IssueEvent $event)
    {
        $container = UbirimiContainer::get();

        $container['issue.email']->emailIssueComment($event->getIssue(), $event->getProject(), $event->getExtra());
    }

    public function onIssueLinkEmail(IssueEvent $event)
    {
        $container = UbirimiContainer::get();

        $container['issue.email']->emailIssueLink($event->getExtra()['issueId'], $event->getProject(), $event->getExtra()['comment']);
    }

    public function onIssueShareEmail(IssueEvent $event)
    {
        $container = UbirimiContainer::get();

        $container['issue.email']->emailIssueShare($event->getIssue(), $event->getExtra()['userIds'], $event->getExtra()['noteContent']);
    }

    public static function getSubscribedEvents()
    {
        return array(
            YongoEvents::YONGO_ISSUE => 'onIssue',
            YongoEvents::YONGO_ISSUE_EMAIL => 'onIssueEmail',
            YongoEvents::YONGO_ISSUE_COMMENT_EMAIL => 'onIssueCommentEmail',
            YongoEvents::YONGO_ISSUE_LINK_EMAIL => 'onIssueLinkEmail',
            YongoEvents::YONGO_ISSUE_SHARE_EMAIL => 'onIssueShareEmail'
        );
    }
}