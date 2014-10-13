<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_GET['id'];
    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);
    $projectId = $issue['issue_project_id'];
    $issueProject = $this->getRepository('yongo.project.project')->getById($projectId);

    // before going further, check to is if the issue project belongs to the client
    if ($clientId != $issueProject['client_id']) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

    if (isset($_POST['move_issue_step_1'])) {
        $newProjectId = Util::cleanRegularInputField($_POST['move_to_project']);
        $newIssueTypeId = Util::cleanRegularInputField($_POST['move_to_issue_type']);

        UbirimiContainer::get()['session']->set('move_issue', array(
            'id' => $issueId,
            'new_project' => $newProjectId,
            'new_type' => $newIssueTypeId,
            'new_component' => array(),
            'new_fix_version' => array(),
            'new_affects_version' => array(),
            'sub_task_old_issue_type' => array(),
            'sub_task_new_issue_type' => array())
        );

        $childrenIssues = null;
        if ($issue['parent_id'] == null) {
            $childrenIssues = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('parent_id' => $issue['id']));
        }

        $newProjectIssueTypes = $this->getRepository('yongo.project.project')->getIssueTypes($newProjectId, 0, 'array', 'id');
        $selectIssueTypeForSubstaks = false;

        if ($childrenIssues) {
            while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
                if (!in_array($childIssue['type'], $newProjectIssueTypes)) {
                    $tempArray = UbirimiContainer::get()['session']->get('move_issue/sub_task_old_issue_type');
                    $tempArray[] = $childIssue['type'];
                    UbirimiContainer::get()['session']->set('move_issue/sub_task_old_issue_type', $tempArray);
                    $selectIssueTypeForSubtasks = true;
                }
            }
        }

        if ($selectIssueTypeForSubtasks) {
            header('Location: /yongo/issue/move/subtask-issue-type/' . $issueId);
        } else {
            // check if step 2 is necessary
            $newWorkflow = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($newProjectId, $newIssueTypeId);
            $newStatuses = Workflow::getLinkedStatuses($newWorkflow['id']);

            $step2Necessary = true;
            while ($newStatuses && $status = $newStatuses->fetch_array(MYSQLI_ASSOC)) {
                if ($status['linked_issue_status_id'] == $issue['status']) {
                    $step2Necessary = false;
                }
            }

            if ($step2Necessary) {
                header('Location: /yongo/issue/move/status/' . $issueId);
            } else {
                UbirimiContainer::get()['session']->set('move_issue/new_status', $issue['status']);
                header('Location: /yongo/issue/move/fields/' . $issueId);
            }
        }
        die();
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Move Issue - ' . $issue['project_code'] . '-' . $issue['nr'] . ' ' . $issue['summary'];
    $projectForMoving = Client::getProjectsByPermission($session->get('client/id'), $loggedInUserId, Permission::PERM_CREATE_ISSUE);
    $firstProject = $projectForMoving->fetch_array(MYSQLI_ASSOC);

    $moveToIssueTypes = $this->getRepository('yongo.project.project')->getIssueTypes($firstProject['id'], 0);
    $projectForMoving->data_seek(0);
    $menuSelectedCategory = 'issue';

    require_once __DIR__ . '/../../../Resources/views/issue/move/MoveStep1.php';