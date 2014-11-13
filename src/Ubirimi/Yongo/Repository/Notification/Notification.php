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

namespace Ubirimi\Yongo\Repository\Notification;

class Notification
{
    const NOTIFICATION_TYPE_CURRENT_ASSIGNEE = 'current_assignee';
    const NOTIFICATION_TYPE_REPORTER = 'reporter';
    const NOTIFICATION_TYPE_CURRENT_USER = 'current_user';
    const NOTIFICATION_TYPE_PROJECT_LEAD = 'project_lead';
    const NOTIFICATION_TYPE_COMPONENT_LEAD = 'component_lead';
    const NOTIFICATION_TYPE_USER = 'user';
    const NOTIFICATION_TYPE_GROUP = 'group';
    const NOTIFICATION_TYPE_PROJECT_ROLE = 'role';
    const NOTIFICATION_TYPE_ALL_WATCHERS = 'all_watchers';
    const NOTIFICATION_TYPE_USER_PICKER_MULTIPLE_SELECTION = 'user_picker_multiple_selection';
}