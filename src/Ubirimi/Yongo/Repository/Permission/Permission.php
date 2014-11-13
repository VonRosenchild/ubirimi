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

namespace Ubirimi\Yongo\Repository\Permission;

use Ubirimi\Container\UbirimiContainer;

class Permission {
    const PERMISSION_TYPE_CURRENT_ASSIGNEE = 'current_assignee';
    const PERMISSION_TYPE_REPORTER = 'reporter';
    const PERMISSION_TYPE_PROJECT_LEAD = 'project_lead';
    const PERMISSION_TYPE_USER = 'user';
    const PERMISSION_TYPE_GROUP = 'group';
    const PERMISSION_TYPE_PROJECT_ROLE = 'role';

    const PERM_ADMINISTER_PROJECTS = 1;
    const PERM_BROWSE_PROJECTS = 2;

    const PERM_CREATE_ISSUE = 3;
    const PERM_EDIT_ISSUE = 4;
    const PERM_ASSIGN_ISSUE = 5;
    const PERM_ASSIGNABLE_USER = 6;
    const PERM_RESOLVE_ISSUE = 7;
    const PERM_CLOSE_ISSUE = 8;
    const PERM_MODIFY_REPORTER = 9;
    const PERM_DELETE_ISSUE = 10;

    const PERM_ADD_COMMENTS = 11;
    const PERM_EDIT_ALL_COMMENTS = 12;
    const PERM_EDIT_OWN_COMMENTS = 13;
    const PERM_DELETE_ALL_COMMENTS = 14;
    const PERM_DELETE_OWN_COMMENTS = 15;

    const PERM_CREATE_ATTACHMENTS = 16;
    const PERM_DELETE_ALL_ATTACHMENTS = 17;
    const PERM_DELETE_OWN_ATTACHMENTS = 18;

    const PERM_SET_SECURITY_LEVEL = 19;
    const PERM_LINK_ISSUE = 20;
    const PERM_MOVE_ISSUE = 21;

    // time tracking permissions
    const PERM_WORK_ON_ISSUE = 22;
    const PERM_EDIT_OWN_WORKLOGS = 23;
    const PERM_EDIT_ALL_WORKLOGS = 24;
    const PERM_DELETE_OWN_WORKLOGS = 25;
    const PERM_DELETE_ALL_WORKLOGS = 26;

    // voters and watchers
    const PERM_VIEW_VOTERS_AND_WATCHERS = 27;
    const PERM_MANAGE_WATCHERS = 28;

    public function getAll() {
        $query = "select sys_permission.id, sys_permission.name, sys_permission.description " .
            "from sys_permission";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByCategory($categoryId) {
        $query = "select sys_permission.id, sys_permission.name, sys_permission.description " .
            "from sys_permission " .
            "where sys_permission_category_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getCategories() {
        $query = "select sys_permission_category.* " .
            "from sys_permission_category";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
