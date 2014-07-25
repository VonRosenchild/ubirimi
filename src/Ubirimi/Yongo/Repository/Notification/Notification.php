<?php

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