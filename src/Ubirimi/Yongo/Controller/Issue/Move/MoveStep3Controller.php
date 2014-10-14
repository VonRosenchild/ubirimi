<?php

namespace Ubirimi\Yongo\Controller\Issue\Move;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Issue\Version;
use Ubirimi\Yongo\Repository\Permission\Permission;


class MoveStep3Controller extends UbirimiController
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

        if (isset($_POST['move_issue_step_3'])) {

            $newIssueComponents = $_POST['new_component'];
            $newIssueFixVersions = $_POST['new_fix_version'];
            $newIssueAffectsVersions = $_POST['new_affects_version'];

            if (array_key_exists('new_assignee', $_POST)) {
                $session->set('move_issue/new_assignee', $_POST['new_assignee']);
            }

            $session->set('move_issue/new_component', $newIssueComponents);
            $session->set('move_issue/new_fix_version', $newIssueFixVersions);
            $session->set('move_issue/new_affects_version', $newIssueAffectsVersions);

            header('Location: /yongo/issue/move/confirmation/' . $issueId);
            die();
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];

        $menuSelectedCategory = 'issue';

        $targetProjectId = $session->get('move_issue/new_project');
        $targetProjectComponents = $this->getRepository('yongo.project.project')->getComponents($targetProjectId);
        $targetVersions = $this->getRepository('yongo.project.project')->getVersions($targetProjectId);

        $issueComponents = Component::getByIssueIdAndProjectId($issue['id'], $projectId);
        $issueFixVersions = Version::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
        $issueAffectedVersions = Version::getByIssueIdAndProjectId($issue['id'], $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);

        $sourceAssignee = $issue['assignee'];
        $assignableUsersTargetProjectArray = $this->getRepository('yongo.project.project')->getUsersWithPermission($session->get('move_issue/new_project'), Permission::PERM_ASSIGNABLE_USER, 'array');

        $assigneeChanged = true;

        if ($sourceAssignee) {
            for ($i = 0; $i < count($assignableUsersTargetProjectArray); $i++) {
                if ($sourceAssignee == $assignableUsersTargetProjectArray[$i]['user_id']) {
                    $assigneeChanged = false;
                    break;
                }
            }
        }

        $actionTaken = false;
        if ((($issueComponents || $issueFixVersions || $issueAffectedVersions) && ($targetProjectComponents || $targetVersions)) || $assigneeChanged) {
            $actionTaken = true;
        }
        $newStatusName = Settings::getById($session->get('move_issue/new_status'), 'status', 'name');

        $newProject = $this->getRepository('yongo.project.project')->getById($session->get('move_issue/new_project'));
        $newProjectName = $newProject['name'];
        $newTypeName = Settings::getById($session->get('move_issue/new_type'), 'type', 'name');

        return $this->render(__DIR__ . '/../../../Resources/views/issue/move/MoveStep3.php', get_defined_vars());
    }
}