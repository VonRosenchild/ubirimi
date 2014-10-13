<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Field\Field;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));

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

        $project = $this->getRepository('yongo.project.project')->getById($projectId);

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

        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'ADD Yongo issue ' . $project['code'] . '-' . $issue['nr']);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

        // clean the search information
        $session->remove('array_ids');
        $session->remove('last_search_parameters');

        return new Response('New Issue Created <a href="/yongo/issue/' . $issue['id'] . '">' . $project['code'] . '-' . $issue['nr'] . '</a>');
    }
}
