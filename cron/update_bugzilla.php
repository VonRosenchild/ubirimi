<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\GeneralTaskQueue;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Issue\Comment;

/* check locking mechanism */
if (file_exists('update_bugzilla.lock')) {
    $fp = fopen('update_bugzilla.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for update buzilla task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$conn = UbirimiContainer::get()['db.connection'];
$bugzillaConn = UbirimiContainer::get()['bugzilla.connection'];

$bugzillaConn->autocommit(false);

$PILOT_TEAM_START_DATE = date('2014-07-31 00:00:00');

$severityConstants = array(
    1 => 'S1.Blocker (crashes)',
    2 => 'S2.Critical (unusable)',
    3 => 'S3.normal (not fully functional)',
    4 => 'S4.minor (cosmetic)',
    5 => 'S5.Enhancement (nice to have)',
    6 => 'major'
);

try {
    $newIssuesUpdated = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array(
        'date_updated_after' => $PILOT_TEAM_START_DATE
    ));

    $newIssuesCreated = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array(
        'date_created_after' => $PILOT_TEAM_START_DATE
    ));

    $newIssuesUpdated = $newIssuesUpdated->fetch_all(MYSQLI_ASSOC);
    $newIssuesCreated = $newIssuesCreated->fetch_all(MYSQLI_ASSOC);

    if (0 == count($newIssuesCreated && 0 == $newIssuesUpdated)) {
        die("\nNo new issues\n");
    }

    $newIssues = $newIssuesUpdated;

    foreach ($newIssuesCreated as $newIssueCreated) {
        $found = false;
        foreach ($newIssues as $newIssue) {
            if ($newIssue['id'] == $newIssueCreated['id']) {
                $found = true;
            }
        }

        if (false === $found) {
            $newIssues[] = $newIssueCreated;
        }
    }


    foreach ($newIssues as $newIssue) {
        /* check if the issue is new all together or only some fields have changed */
        $bug = getBugzillaBugBasedOnUbirimiIssue($newIssue['summary'], $newIssue['date_created']);

        /* bugzilla does not have the issue yet */
        if (null === $bug) {
            processNewBugzillaBug($newIssue);
        } else {
            /* if is does, delete the bugzilla issue and crete a new one */
            deleteBugzillaBug($bug['bug_id']);

            processNewBugzillaBug($newIssue);
        }
    }

    $bugzillaConn->commit();

} catch (\Exception $e) {
    $bugzillaConn->rollback();

    echo "\n" . $e->getMessage();
}

if (null !== $fp) {
    fclose($fp);
}

function deleteBugzillaBug($bugId)
{
    $query = "delete from longdescs where bug_id = ?";

    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);

    $stmt->bind_param("i", $bugId);
    $stmt->execute();

    $query = "delete from bugs where bug_id = ?";

    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);

    $stmt->bind_param("i", $bugId);
    $stmt->execute();
}

function processNewBugzillaBug($newIssue)
{
    global $severityConstants;

    $issueComponent = Component::getByIssueIdAndProjectId($newIssue['id'], $newIssue['issue_project_id']);
    $issueVersion = IssueVersion::getByIssueIdAndProjectId($newIssue['id'], $newIssue['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
    $project = Project::getById($newIssue['issue_project_id']);
    $severity = CustomField::getCustomFieldsDataByFieldId($newIssue['id'], 29403);
    $comments = Comment::getByIssueId($newIssue['id']);

    if (null !== $issueComponent) {
        $issueComponent = $issueComponent->fetch_array(MYSQLI_ASSOC);
    } else {
        echo "Issue " . $newIssue['summary'] . " skipped. Missing component.\n";
        return -1;
    }

    if (null == $newIssue['resolution_name']) {
        $newIssue['resolution_name'] = '';
    }

    if (null == $issueVersion) {
        $issueVersion = 'unspecified';
    }

    if (isset($severityConstants[$severity['value']])) {
        $severity = $severityConstants[$severity['value']];
    } else {
        $severity = $severityConstants[6];
    }

    $bugId = insertBugzillaBug(
        $newIssue['assignee'],
        $severity,
        $newIssue['status_name'],
        $issueComponent,
        $newIssue['date_created'],
        $newIssue['date_updated'],
        $newIssue['priority_name'],
        $project,
        $newIssue['reporter'],
        $newIssue['resolution_name'],
        $newIssue['summary'],
        $issueVersion
    );

    insertBugzillaComments($bugId, $comments, $newIssue['summary']);
}

function insertBugzillaBug($assigned_to, $bug_severity, $bug_status, $component, $creation_ts, $date_updated, $priority, $project, $reporter, $resolution, $short_desc, $version)
{
    $ubirimiUser = User::getById($assigned_to);
    $ubirimiReporter = User::getById($reporter);

    $bugzillaUser = getBugzillaUserByEmail($ubirimiUser['email']);
    $bugzillaReporter = getBugzillaUserByEmail($ubirimiReporter['email']);

    $bugzillaProduct = getBuzillaProduct($project);
    $bugzillaComponent = getBugzillaComponent($component);

    $repPlatform = 'All';
    $everconfirmed = 1;
    $bug_file_loc = '';
    $op_sys = '';
    if (empty($date_updated)) {
        $date_updated = '0000-00-00 00:00:00';
    }

    $query = "INSERT INTO bugs(
                assigned_to, bug_severity, bug_status, component_id, creation_ts, delta_ts,
                priority, product_id, reporter, resolution, short_desc, version, rep_platform, everconfirmed, bug_file_loc, op_sys)
              VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query)) {
        $stmt->bind_param(
            "ississsiissssiss",
            $bugzillaUser[0]['userid'], $bug_severity, $bug_status, $bugzillaComponent[0]['id'],
            $creation_ts, $date_updated, $priority, $bugzillaProduct[0]['id'],
            $bugzillaReporter[0]['userid'], $resolution, $short_desc, $version, $repPlatform, $everconfirmed, $bug_file_loc, $op_sys
        );

        $stmt->execute();

        return UbirimiContainer::get()['bugzilla.connection']->insert_id;
    }
}

function insertBugzillaComments($bugId, $comments, $short_desc)
{

    $allComments = '';

    if (empty($comments)) {
        return;
    }

    foreach ($comments as $comment) {
        $bugzillaUser = getBugzillaUserByEmail($comment['email']);
        $workTime = '0.0';
        $isPrivate = 0;
        $already_wrapped = 0;
        $type = 0;
        $extra_data = null;

        $query = "INSERT INTO longdescs(
                bug_id, who, bug_when, work_time, thetext, isprivate, already_wrapped, type, extra_data)
              VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);

        $stmt->bind_param(
            "iisssiiis",
            $bugId, $bugzillaUser[0]['userid'], $comment['date_created'], $workTime, $comment['content'], $isPrivate, $already_wrapped, $type, $extra_data
        );

        $stmt->execute();

        $allComments .= $comment['content'];
    }

    $query = "INSERT INTO bugs_fulltext(
                bug_id, short_desc, comments, comments_noprivate)
              VALUES(?, ?, ?, ?)";

    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);

    $stmt->bind_param(
        "isss",
        $bugId, $short_desc, $allComments, $allComments
    );

    $stmt->execute();
}

function getBugzillaUserByEmail($email)
{
    $query = "SELECT *
            FROM profiles
            where login_name = ?";


    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBuzillaProduct($project)
{
    $query = "SELECT *
            FROM products
            WHERE name = ?
              AND description = ?";


    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);
    $stmt->bind_param("ss", $project['name'], $project['description']);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBugzillaComponent($component)
{
    $query = "SELECT *
            FROM components
            WHERE name = ?
              AND description = ?";


    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);
    $stmt->bind_param("ss", $component['name'], $component['description']);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBugzillaBugBasedOnUbirimiIssue($shortDesc, $creationTs)
{
    $query = 'SELECT *
                FROM bugs
                WHERE short_desc = ?
                  AND creation_ts = ?';

    if ($stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query)) {
        $stmt->bind_param("ss", $shortDesc, $creationTs);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_array(MYSQLI_ASSOC);
        }

        return null;
    }

    throw new \Exception('getBugzillaBugBasedOnUbirimiIssue Exception');
}
