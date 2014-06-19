<?php

namespace Ubirimi\Yongo\Event;

/**
 * Events triggered by Yongo product
 */
class YongoEvents
{
    /**
     * Yongo Issue Event
     *
     * Event triggered whenever something that involves an Issue happens
     * The Event will pass a \Ubirimi\Yongo\Event\IssueEvent object.
     *
     * @var string
     */
    const YONGO_ISSUE = 'yongo.issue';

    /**
     * Yongo Issue Email Event
     *
     * Event triggered for email notification action.
     * The Event will pass a \Ubirimi\Yongo\Event\IssueEvent object.
     */
    const YONGO_ISSUE_EMAIL = 'yongo.issue_email';

    /**
     * Yongo Issue Comment Email Event
     *
     * Event triggered when a comment is added to an issue.
     * The Event will pass a \Ubirimi\Yongo\Event\IssueEvent object.
     */
    const YONGO_ISSUE_COMMENT_EMAIL = 'yongo.issue_comment_email';
    /**
     * Yongo Issue Link Email Event
     *
     * Event triggered when an issue is linked to another issue.
     * The Event will pass a \Ubirimi\Yongo\Event\IssueEvent object.
     */
    const YONGO_ISSUE_LINK_EMAIL = 'yongo.issue_link_email';

    /**
     * Yongo Issue Share Email Event
     *
     * Event triggered when an issue is shared.
     * The Event will pass a \Ubirimi\Yongo\Event\IssueEvent object.
     */
    const YONGO_ISSUE_SHARE_EMAIL = 'yongo.issue_share_email';

    /**
     * Yongo Issue Update Assignee Email Event
     */
    const YONGO_ISSUE_UPDATE_ASSIGNEE_EMAIL = 'yongo.issue_update_assignee';
}