<?php

namespace Ubirimi\Yongo\Controller\Issue\Move;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;

class MoveStep4Controller extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $issueId = $request->get('id');
        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);
        $projectId = $issue['issue_project_id'];
        $issueProject = $this->getRepository('yongo.project.project')->getById($projectId);

        // before going further, check to is if the issue project belongs to the client
        if ($clientId != $issueProject['client_id']) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        if ($request->request->get('move_issue_step_4')) {
            $currentDate = Util::getServerCurrentDateTime();

            $oldIssueData = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
            $oldIssueData['component'] = $this->getRepository('yongo.issue.component')->getByIssueIdAndProjectId($issueId, $projectId, 'array', 'name');
            if ($oldIssueData['component'] == null) {
                $oldIssueData['component'] = array();
            }
            $oldIssueData['affects_version'] = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issueId, $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_AFFECTED_VERSION_FLAG, 'array', 'name');
            if ($oldIssueData['affects_version'] == null) {
                $oldIssueData['affects_version'] = array();
            }
            $oldIssueData['fix_version'] = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issueId, $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_FIX_VERSION_FLAG, 'array', 'name');
            if ($oldIssueData['fix_version'] == null) {
                $oldIssueData['fix_version'] = array();
            }

            $this->getRepository('yongo.issue.component')->deleteByIssueId($issueId);
            $this->getRepository('yongo.issue.version')->deleteByIssueIdAndFlag($issueId, $this->getRepository('yongo.issue.issue')->ISSUE_FIX_VERSION_FLAG);
            $this->getRepository('yongo.issue.version')->deleteByIssueIdAndFlag($issueId, $this->getRepository('yongo.issue.issue')->ISSUE_AFFECTED_VERSION_FLAG);

            if ($session->has('move_issue/new_assignee')) {
                $this->getRepository('yongo.issue.issue')->updateAssigneeRaw($issueId, $session->get('move_issue/new_assignee'));
            }

            if (count($session->get('move_issue/new_component'))) {
                $this->getRepository('yongo.issue.issue')->addComponentVersion($issueId, $session->get('move_issue/new_component'), 'issue_component');
            }

            if (count($session->get('move_issue/new_fix_version'))) {
                $this->getRepository('yongo.issue.issue')->addComponentVersion($issueId, $session->get('move_issue/new_fix_version'), 'issue_version', $this->getRepository('yongo.issue.issue')->ISSUE_FIX_VERSION_FLAG);
            }

            if (count($session->get('move_issue/new_affects_version'))) {
                $this->getRepository('yongo.issue.issue')->addComponentVersion($issueId, $session->get('move_issue/new_affects_version'), 'issue_version', $this->getRepository('yongo.issue.issue')->ISSUE_AFFECTED_VERSION_FLAG);
            }

            $newProjectId = $session->get('move_issue/new_project');

            // move the issue
            $this->getRepository('yongo.issue.issue')->move($issueId, $newProjectId, $session->get('move_issue/new_type'), $session->get('move_issue/sub_task_new_issue_type'));

            $session->remove('move_issue');
            $newIssueData = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
            $fieldChanges = $this->getRepository('yongo.issue.issue')->computeDifference($oldIssueData, $newIssueData, array(), array());
            $this->getRepository('yongo.issue.issue')->updateHistory($issueId, $loggedInUserId, $fieldChanges, $currentDate);

            $issueEvent = new IssueEvent(null, null, IssueEvent::STATUS_UPDATE, array('oldIssueData' => $oldIssueData, 'fieldChanges' => $fieldChanges));
            $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'MOVE Yongo issue ' . $oldIssueData['project_code'] . '-' . $oldIssueData['nr']);

            UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

            header('Location: ' . LinkHelper::getYongoIssueViewLinkJustHref($issueId));
            die();
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

        $previousData = $session->get('move_issue');
        $menuSelectedCategory = 'issue';

        $newStatusName = $this->getRepository('yongo.issue.settings')->getById($session->get('move_issue/new_status'), 'status', 'name');

        $newProject = $this->getRepository('yongo.project.project')->getById($session->get('move_issue/new_project'));
        $newTypeName = $this->getRepository('yongo.issue.settings')->getById($session->get('move_issue/new_type'), 'type', 'name');

        $issueComponents = $this->getRepository('yongo.issue.component')->getByIssueIdAndProjectId($issue['id'], $projectId);
        $issueFixVersions = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issue['id'], $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_FIX_VERSION_FLAG);
        $issueAffectedVersions = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issue['id'], $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_AFFECTED_VERSION_FLAG);

        $newIssueComponents = null;
        $newIssueFixVersions = null;
        $newIssueAffectsVersions = null;

        if (count($session->get('move_issue/new_component'))) {
            $newIssueComponents = $this->getRepository('yongo.issue.component')->getByIds($session->get('move_issue/new_component'));
        }

        if (count($session->get('move_issue/new_fix_version'))) {
            $newIssueFixVersions = $this->getRepository('yongo.issue.version')->getByIds($session->get('move_issue/new_fix_version'));
        }

        if (count($session->get('move_issue/new_affects_version'))) {
            $newIssueAffectsVersions = $this->getRepository('yongo.issue.version')->getByIds($session->get('move_issue/new_affects_version'));
        }

        $newUserAssignee = $this->getRepository('ubirimi.user.user')->getById($session->get('move_issue/new_assignee'));

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep4.php', get_defined_vars());
    }
}