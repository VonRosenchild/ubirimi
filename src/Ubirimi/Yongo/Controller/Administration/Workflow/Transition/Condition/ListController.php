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

namespace Ubirimi\Yongo\Controller\Administration\Workflow\Transition\Condition;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $workflowDataId = $request->get('id');
        $workflowData = $this->getRepository(Workflow::class)->getDataById($workflowDataId);
        $workflow = $this->getRepository(Workflow::class)->getMetaDataById($workflowData['workflow_id']);

        if ($workflow['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $condition = $this->getRepository(WorkflowCondition::class)->getByTransitionId($workflowDataId);
        $conditionString = $condition['definition_data'];

        $text_open_bracket = '<a class="button">(</a> ';
        $text_closed_bracket = '<a class="button">)</a> ';
        $text_operator_and = '<a class="button">AND</a> ';
        $text_operator_or = '<a class="button">OR</a> ';
        $conditionString = str_replace("(", $text_open_bracket, $conditionString);
        $conditionString = str_replace(")", $text_closed_bracket, $conditionString);
        $conditionString = str_replace("[[AND]]", $text_operator_and, $conditionString);
        $conditionString = str_replace("[[OR]]", $text_operator_or, $conditionString);

        preg_match_all('/cond_id=[0-9]+/', $conditionString, $conditions);

        for ($i = 0; $i < count($conditions); $i++) {
            $conditionId = (int)str_replace('cond_id=', '', $conditions[$i]);

            $text = '';
            switch ($conditionId) {
                case WorkflowCondition::CONDITION_ONLY_ASSIGNEE:
                    $text = 'Only the assignee of the issue can execute the transition';
                    break;
                case WorkflowCondition::CONDITION_ONLY_REPORTER:
                    $text = 'Only the reporter of the issue can execute the transition';
                    break;
            }

            $text = '<a style="text-align:center; width: 200px; height: 30px; white-space: normal;" class="button">' . $text . '</a> ';
            $conditionString = str_replace($conditions[$i], $text, $conditionString);
        }

        preg_match_all('/perm_id=[0-9]+/', $conditionString, $permissions);
        $permissions = $permissions[0];

        for ($i = 0; $i < count($permissions); $i++) {
            $permissionId = (int)str_replace('perm_id=', '', $permissions[$i]);

            $text = '';
            switch ($permissionId) {

                case Permission::PERM_ADMINISTER_PROJECTS:
                    $text = 'Only users with Administer Projects permission can execute this transition';
                    break;
                case Permission::PERM_BROWSE_PROJECTS:
                    $text = 'Only users with Browse Projects permission can execute this transition';
                    break;
                case Permission::PERM_CREATE_ISSUE:
                    $text = 'Only users with Create Issues permission can execute this transition';
                    break;
                case Permission::PERM_EDIT_ISSUE:
                    $text = 'Only users with Edit Issues permission can execute this transition';
                    break;
                case Permission::PERM_CLOSE_ISSUE:
                    $text = 'Only users with Close Issues permission can execute this transition';
                    break;

                case Permission::PERM_RESOLVE_ISSUE:
                    $text = 'Only users with Resolve Issues permission can execute this transition';
                    break;

                case Permission::PERM_ASSIGN_ISSUE:
                    $text = 'Only users with Assign Issues permission can execute this transition';
                    break;
                case Permission::PERM_ASSIGNABLE_USER:
                    $text = 'Only users with Assignable User permission can execute this transition';
                    break;

                case Permission::PERM_MODIFY_REPORTER:
                    $text = 'Only users with Modify Reporter permission can execute this transition';
                    break;
                case Permission::PERM_DELETE_ISSUE:
                    $text = 'Only users with Delete Issue permission can execute this transition';
                    break;

                case Permission::PERM_SET_SECURITY_LEVEL:
                    $text = 'Only users with Set Issue Security permission can execute this transition';
                    break;

                case Permission::PERM_LINK_ISSUE:
                    $text = 'Only users with Link Issues permission can execute this transition';
                    break;
                case Permission::PERM_MOVE_ISSUE:
                    $text = 'Only users with Move Issues permission can execute this transition';
                    break;
                case Permission::PERM_ADD_COMMENTS:
                    $text = 'Only users with Add Comments permission can execute this transition';
                    break;

                case Permission::PERM_EDIT_ALL_COMMENTS:
                    $text = 'Only users with Edit All Comments permission can execute this transition';
                    break;

                case Permission::PERM_EDIT_OWN_COMMENTS:
                    $text = 'Only users with Edit Own Comments permission can execute this transition';
                    break;

                case Permission::PERM_DELETE_ALL_COMMENTS:
                    $text = 'Only users with Delete All Comments permission can execute this transition';
                    break;

                case Permission::PERM_DELETE_OWN_COMMENTS:
                    $text = 'Only users with Delete Own Comments permission can execute this transition';
                    break;
                case Permission::PERM_CREATE_ATTACHMENTS:
                    $text = 'Only users with Create Attachments permission can execute this transition';
                    break;
                case Permission::PERM_DELETE_ALL_ATTACHMENTS:
                    $text = 'Only users with Delete All Attachments permission can execute this transition';
                    break;

                case Permission::PERM_DELETE_OWN_ATTACHMENTS:
                    $text = 'Only users with Delete Own Attachments permission can execute this transition';
                    break;

                case Permission::PERM_WORK_ON_ISSUE:
                    $text = 'Only users with Work On Issues permission can execute this transition';
                    break;
                case Permission::PERM_EDIT_OWN_WORKLOGS:
                    $text = 'Only users with Edit Own Worklogs permission can execute this transition';
                    break;
                case Permission::PERM_EDIT_ALL_WORKLOGS:
                    $text = 'Only users with Edit All Worklogs permission can execute this transition';
                    break;
                case Permission::PERM_DELETE_OWN_WORKLOGS:
                    $text = 'Only users with Delete Own Worklogs permission can execute this transition';
                    break;

                case Permission::PERM_DELETE_ALL_WORKLOGS:
                    $text = 'Only users with Delete All Worklogs permission can execute this transition';
                    break;
            }

            $text = '<a style="text-align:center; width: 220px; height: 30px; white-space: normal;" class="button">' . $text . '</a> ';
            $conditionString = str_replace($permissions[$i], $text, $conditionString);
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Transition Conditions';

        return $this->render(__DIR__ . '/../../../../../Resources/views/administration/workflow/transition/condition/List.php', get_defined_vars());
    }
}