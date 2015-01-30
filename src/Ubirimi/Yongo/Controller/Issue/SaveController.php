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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));

        $timeTrackingDefaultUnit = $session->get('yongo/settings/time_tracking_default_unit');

        $projectId = $request->request->get('project_id');
        $issueId = $request->request->get('issue_id');
        $attachIdsToBeKept = $request->request->get('attach_ids');

        $fieldTypes = $request->request->get('field_types');
        $fieldValues = $request->request->get('field_values');

        $fieldTypesCustom = $request->request->get('field_types_custom');
        $fieldValuesCustom = $request->request->get('field_values_custom');

        if (!is_array($attachIdsToBeKept)) {
            $attachIdsToBeKept = array();
        }

        $issueSystemFieldsData = array();
        $issueCustomFieldsData = array();

        for ($i = 0; $i < count($fieldTypes); $i++) {
            if ($fieldValues[$i] != 'null' && $fieldValues[$i] != '') {
                $issueSystemFieldsData[$fieldTypes[$i]] = $fieldValues[$i];
            }
            else {
                $issueSystemFieldsData[$fieldTypes[$i]] = null;
            }
        }

        for ($i = 0; $i < count($fieldTypesCustom); $i++) {
            if ($fieldValuesCustom[$i] != 'null' && $fieldValuesCustom[$i] != '') {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = $fieldValuesCustom[$i];
            } else {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = null;
            }
        }

        if (!$projectId) {
            $projectId = $issueSystemFieldsData['project'];
        }

        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if (array_key_exists(Field::FIELD_ASSIGNEE_CODE, $issueSystemFieldsData)) {
            // assignee field is placed on screen
            if ($issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] == -1) {
                $issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] = null;
            }
        } else {
            // put the assignee as the project default assignee
            $issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] = $project['lead_id'];
        }

        $issueSystemFieldsData['helpdesk_flag'] = 0;
        if ($session->get("selected_product_id") == SystemProduct::SYS_PRODUCT_HELP_DESK) {
            $issueSystemFieldsData['helpdesk_flag'] = 1;
        }
        $issueSystemFieldsData['user_reported_ip'] = Util::getClientIP();

        $issue = UbirimiContainer::get()['issue']->save(
            $project,
            $issueSystemFieldsData,
            $issueId,
            $timeTrackingDefaultUnit,
            $projectId,
            $issueCustomFieldsData,
            $attachIdsToBeKept,
            $clientSettings,
            $session->get('user/id'),
            $session->get('client/id')
        );

        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_NEW);

        $this->getLogger()->addInfo('ADD Yongo issue ' . $project['code'] . '-' . $issue['nr'], $this->getLoggerContext());

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);

        // clean the search information
        $session->remove('array_ids');
        $session->remove('last_search_parameters');

        return new Response('New Issue Created <a href="/yongo/issue/' . $issue['id'] . '">' . $project['code'] . '-' . $issue['nr'] . '</a>');
    }
}
