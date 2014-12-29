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
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('issue_id');
        $fieldTypes = $request->get('field_types');
        $fieldValues = $request->get('field_values');

        $loggedInUserId = $session->get('user/id');

        $fieldTypesCustom = $request->get('field_types_custom');
        $fieldValuesCustom = $request->get('field_values_custom');

        // todo: de ce este aceasta variabila nefolosita

        $attachIds = $request->get('attach_ids');
        $attachIdsToBeKept = $request->get('attach_ids');

        if (!is_array($attachIdsToBeKept)) {
            $attachIdsToBeKept = array();
        }

        $oldIssueData = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        $newIssueData = array();
        $newIssueData['issue_project_id'] = $oldIssueData['issue_project_id'];
        $newIssueCustomFieldsData = array();

        for ($i = 0; $i < count($fieldTypes); $i++) {
            if ($fieldValues[$i] != 'null' && $fieldValues[$i] != '') {
                if ($fieldTypes[$i] == Field::FIELD_DUE_DATE_CODE) {
                    $fieldValues[$i] = \DateTime::createFromFormat('Y-m-d', $fieldValues[$i])->format('Y-m-d');
                }
                $newIssueData[$fieldTypes[$i]] = $fieldValues[$i];
            } else {
                $newIssueData[$fieldTypes[$i]] = null;
            }
        }

        if ($fieldTypesCustom) {
            for ($i = 0; $i < count($fieldTypesCustom); $i++) {
                if ($fieldValuesCustom[$i] != 'null' && $fieldValuesCustom[$i] != '') {
                    $newIssueCustomFieldsData[$fieldTypesCustom[$i]] = $fieldValuesCustom[$i];
                } else {
                    $newIssueCustomFieldsData[$fieldTypesCustom[$i]] = null;
                }
            }
        }

        if (array_key_exists(Field::FIELD_ASSIGNEE_CODE, $newIssueData)) {
            // assignee field is placed on screen
            if ($newIssueData[Field::FIELD_ASSIGNEE_CODE] == -1) {
                $newIssueData[Field::FIELD_ASSIGNEE_CODE] = null;
            }
        }

        if (!isset($newIssueData[Field::FIELD_STATUS_CODE])) {
            $newIssueData[Field::FIELD_STATUS_CODE] = $oldIssueData[Field::FIELD_STATUS_CODE];
        }

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository(Issue::class)->updateById($issueId, $newIssueData, $currentDate);

        $oldIssueCustomFieldsData = array();
        foreach ($newIssueCustomFieldsData as $key => $value) {
            $keyData = explode("_", $key);

            $oldIssueCustomFieldsData[$keyData[0]] = $this->getRepository(CustomField::class)->getCustomFieldsDataByFieldId($issueId, $key);
            unset($newIssueCustomFieldsData[$key]);
            $newIssueCustomFieldsData[$keyData[0]] = $value;
        }

        $fieldChanges = $this->getRepository(Issue::class)->computeDifference($oldIssueData, $newIssueData, $oldIssueCustomFieldsData, $newIssueCustomFieldsData);

        $this->getRepository(Issue::class)->updateHistory($issueId, $loggedInUserId, $fieldChanges, $currentDate);

        // check if on the modal there is a comment field
        if (array_key_exists(Field::FIELD_COMMENT_CODE, $newIssueData) && !empty($newIssueData[Field::FIELD_COMMENT_CODE])) {
            $this->getRepository(IssueComment::class)->add($issueId, $loggedInUserId, $newIssueData[Field::FIELD_COMMENT_CODE], $currentDate);
        }

        // update the custom fields value
        if ($fieldTypesCustom) {
            $this->getRepository(CustomField::class)->updateCustomFieldsData($issueId, $newIssueCustomFieldsData, $currentDate);
        }

        Util::manageModalAttachments($issueId, $loggedInUserId, $attachIdsToBeKept);

        UbirimiContainer::get()['session']->remove('added_attachments_in_screen');

        $issueEvent = new IssueEvent(null, null, IssueEvent::STATUS_UPDATE, array('oldIssueData' => $oldIssueData, 'fieldChanges' => $fieldChanges));
        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'UPDATE Yongo issue ' . $oldIssueData['project_code'] . '-' . $oldIssueData['nr']);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

        return new Response('');
    }
}