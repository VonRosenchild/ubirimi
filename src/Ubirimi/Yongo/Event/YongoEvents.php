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

    /**
     * Yongo Issue Add work log
     */
    const YONGO_ISSUE_WORK_LOGGED = 'yongo.issue_work_logged';

    /**
     * Yongo Issue Add Attachment
     */
    const YONGO_ISSUE_ADD_ATTACHMENT = 'yongo.issue_add_attachment';
}