<?php

/**
 * tot ce s-a lucrat separat in bugzilla in timp ce echipa pilot folosea Ubirimi, trebuie adus in Ubirmi.
 */

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\YongoProject;

use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Project\ProjectComponent;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap_cli.php';
require_once __DIR__ . '/bugzilla/repository.php';
require_once __DIR__ . '/bugzilla/connection.php';

UbirimiContainer::get()['db.connection']->autocommit(false);

$clientId = 1959;
$valiId = 1959;

$movidiusProjects = getProducts($connectionBugzilla);
$ubirimiProjects = YongoProject::getByClientId($clientId);
$movidiusUsers = getUsers($connectionBugzilla);
$ubirimiUsers = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
$ubirimiStatuses = getUbirimiStatuses($clientId);
$ubirimiPriorities = getUbirimiPriorities($clientId);
$movidiusComponents = getComponents($connectionBugzilla);
$ubirimiComponents = ProjectComponent::getAll();

$issueNumbers = array();

$severityConstants = array(
    1 => 'S1.Blocker (crashes)',
    2 => 'S2.Critical (unusable)',
    3 => 'S3.normal (not fully functional)',
    4 => 'S4.minor (cosmetic)',
    5 => 'S5.Enhancement (nice to have)',
    6 => 'major'
);

$osConstants = array(
    7 => 'sw_Shave',
    8 => 'sw_Leon',
    9 => 'sw_Android',
    10 => 'sw_PC_Windows',
    11 => 'sw_PC_Linux',
    12 => 'sw_OMAP3',
    13 => 'RTL',
    14 => 'TESTBENCH',
    15 => 'Spec',
    16 => 'Feature',
    17 => 'Feature_mvmon',
    18 => 'Task'
);

$resolutions = array(
    'FIXED' => 9796,
    "WON'T FIX" => 9798,
    'DUPLICATE' => 9799,
    'NO RESOLUTION' => 9801,
    'INVALID' => 9802,
    'LATER' => 9803,
    'REMIND' => 9804,
    'WORKSFORME' => 9805,
    'MOVED' => 1959,
    'Waiting Verification' => 1959
);

$bugs = getBugs($connectionBugzilla);

try {
    foreach ($bugs as $bug) {
        $ubirimiIssue = getUbirimiIssueIdBasedOnBugzillaBug2($bug['short_desc'], $bug['creation_ts']);

        if (null === $ubirimiIssue) {
            insertUbirimiIssue($bug);
        } else {
            /* the bug exists in both Ubirimi and Bugzilla. Check to see if the bug suffered modifications in bugzilla
            since the last import. if yes, delete the bug from Ubirimi and insert the new version. if the versions are
            identical, then SKIP the bug */

            if ($bug['delta_ts'] !== $ubirimiIssue['date_updated']) {
                echo "\nBug:" . $bug['short_desc'] . ' DELETED...';
                Issue::deleteById($ubirimiIssue['id']);
                insertUbirimiIssue($bug);
            }
        }
    }

    UbirimiContainer::get()['db.connection']->commit();
} catch (Exception $e) {
    UbirimiContainer::get()['db.connection']->rollback();
    echo "\n" , $e->getMessage() . "\n";
}

UbirimiContainer::get()['db.connection']->autocommit(true);

function insertUbirimiIssue($bug)
{
    global $movidiusProjects;
    global $ubirimiProjects;
    global $movidiusUsers;
    global $ubirimiUsers;
    global $ubirimiPriorities;
    global $ubirimiStatuses;
    global $connectionBugzilla;
    global $severityConstants;
    global $movidiusComponents;
    global $ubirimiComponents;
    global $issueNumbers;
    global $osConstants;

    if (empty($bug['short_desc'])) {
        return -1;
    }

    echo "\nBug " . $bug['short_desc'] . ' will be INSERTED';

    $projectId = getYongoProjectFromMovidusProject($movidiusProjects, $ubirimiProjects, $bug['product_id']);
    $issue = Issue::addBugzilla(
        array(
            'id' => $projectId
        ),
        $bug['creation_ts'],
        $bug['delta_ts'],
        array(
            'reporter' => getYongoUserFromMovidiusUsers($movidiusUsers, $ubirimiUsers, $bug['reporter']),
            'resolution' => null,
            'priority' => getYongoPriorityFromMovidiusPriority($ubirimiPriorities, $bug['priority']),
            'type' => 15721, //BUG
            'assignee' => getYongoUserFromMovidiusUsers($movidiusUsers, $ubirimiUsers, $bug['assigned_to']),
            'summary' => $bug['short_desc'],
            'description' => '',
            'environment' => null,
            'due_date' => $bug['deadline']
        ),
        1,
        null,
        null,
        getYongoStatusId($ubirimiStatuses, $bug['bug_status'])
    );

    /* install bug severity field -- start */
    $field_id = 29403;
    $value = getUbirimiFieldValue($severityConstants, $bug['bug_severity']);

    if (null !== $value) {
        updateUbirimiBugSeverity($issue[0], $field_id, $value);
    }
    /* install bug severity field -- end */

    /* install bug OS field -- start */
    $os_field_id = 29404;
    $os_value = getUbirimiFieldValue($osConstants, $bug['op_sys']);

    if (null !== $os_value) {
        updateUbirimiBugOS($issue[0], $os_field_id, $os_value);
    }
    /* install bug OS field -- end */


    /* update Ubirimi issue resolution field -- start */
    $resolutionId = isset($resolutions[$bug['resolution']]) ?  $resolutions[$bug['resolution']] : null;

    if (null !== $resolutionId) {
        updateIssueResolution($resolutionId, $issue[0]);
    }
    /* update Ubirimi issue resolution field -- end */

    /* update component -- start */
    if (!empty($bug['component_id'])) {
        Issue::addComponentVersion(
            $issue[0],
            getYongoComponentFromMovidiusComponent($bug['component_id'], $movidiusComponents, $ubirimiComponents),
            'issue_component'
        );
    }
    /* update component -- end */

    /* update issue number -- start */
    YongoProject::updateLastIssueNumber(
        $projectId,
        $issueNumbers[$projectId]
    );
    /* update issue number -- end */

    $comments = getComments($connectionBugzilla, $bug['bug_id']);

    foreach ($comments as $comment) {
        $userId = getYongoUserFromMovidiusUsers($movidiusUsers, $ubirimiUsers, $comment['who']);

        if (null !== $userId) {
            UbirimiContainer::getRepository(IssueComment::class)->add(
                $issue[0],
                $userId,
                $comment['thetext'],
                $comment['bug_when']
            );
        }
    }
}

function getYongoComponentFromMovidiusComponent($movidiusComponentId, $movidiusComponents, $ubirimiComponents)
{
    foreach ($movidiusComponents as $movidiusComponent) {
        if ($movidiusComponentId === $movidiusComponent['id']) {
            foreach ($ubirimiComponents as $ubirimiComponent) {
                if ($ubirimiComponent['project_name'] === $movidiusComponent['product_name'] &&
                    $ubirimiComponent['name'] === $movidiusComponent['name']) {
                    return $ubirimiComponent['id'];
                }
            }
        }
    }

    throw new \Exception('could not find Ubirimi component equivalent for ' . $movidiusComponentId);
}