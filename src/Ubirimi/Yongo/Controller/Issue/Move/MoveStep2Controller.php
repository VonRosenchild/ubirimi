<?php

namespace Ubirimi\Yongo\Controller\Issue\Move;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Issue\Version;


class MoveStep2Controller extends UbirimiController
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
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        if (isset($_POST['move_issue_step_2'])) {
            $newStatusId = Util::cleanRegularInputField($_POST['move_to_status']);

            $session->set('move_issue/new_status', $newStatusId);

            // check if step 3 is necessary

            $issueComponents = $this->getRepository('yongo.issue.component')->getByIssueIdAndProjectId($issue['id'], $projectId);
            $issueFixVersions = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issue['id'], $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_FIX_VERSION_FLAG);
            $issueAffectedVersions = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId($issue['id'], $projectId, $this->getRepository('yongo.issue.issue')->ISSUE_AFFECTED_VERSION_FLAG);

            if ($issueComponents || $issueFixVersions || $issueAffectedVersions) {
                header('Location: /yongo/issue/move/fields/' . $issueId);
            } else {
                header('Location: /yongo/issue/move/confirmation/' . $issueId);
            }

            die();
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];
        $currentWorkflow = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($projectId, $issue['type']);

        $previousData = $session->get('move_issue');
        $newWorkflow = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($previousData['new_project'], $previousData['new_type']);
        $newStatuses = $this->getRepository('yongo.workflow.workflow')->getLinkedStatuses($newWorkflow['id']);
        $menuSelectedCategory = 'issue';

        $newProject = $this->getRepository('yongo.project.project')->getById($session->get('move_issue/new_project'));
        $newProjectName = $newProject['name'];
        $newTypeName = $this->getRepository('yongo.issue.settings')->getById($session->get('move_issue/new_type'), 'type', 'name');

        require_once __DIR__ . '/../../../Resources/views/issue/move/MoveStep2.php';
    }
}
