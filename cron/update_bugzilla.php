<?php


use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\GeneralTaskQueue;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Repository\User\User;

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

$pilotTeamStartDate = date('2014-07-15 00:00:00');

try {
    $bugzillaConn->commit();

    $newIssues = Issue::getByParameters(array(
        'date_created_after' => $pilotTeamStartDate
    ));

    if (null !== $newIssues) {
        $newIssues = $newIssues->fetch_all(MYSQLI_ASSOC);

        foreach ($newIssues as $newIssue) {
            /* check if the issue is new all together or only some fields have changed */

            $bug = getBugzillaBugBasedOnUbirimiIssue($newIssue['summary'], $newIssue['date_created']);
            $issueComponent = IssueComponent::getByIssueIdAndProjectId($newIssue['id'], $newIssue['issue_project_id']);
            $issueVersion = IssueVersion::getByIssueIdAndProjectId($newIssue['id'], $newIssue['issue_project_id'], 'fdsfdsfds');
            /* bugzilla does not have the issue yet */
            if (null === $bug) {
                insertBugzillaBug(
                    $newIssue['user_assigned_id'],
                    ';fjsdh;fsdjkh',
                    $newIssue['status_name'],
                    $issueComponent[0]['id'],
                    $newIssue['date_created'],
                    $newIssue['date_updated'],
                    $newIssue['priority_name'],
                    $newIssue['issue_project_id'],
                    $newIssue['reporter'],
                    $newIssue['resolution_name'],
                    $newIssue['summary'],
                    $issueVersion

                );
            } else {

            }
        }
    }
} catch (\Exception $e) {
    $bugzillaConn->rollback();

    echo "\n" . $e->getMessage();
}

if (null !== $fp) {
    fclose($fp);
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

function insertBugzillaBug($assigned_to, $bug_severity, $bug_status, $component_id, $creation_ts, $date_updated, $priority, $product_id, $reporter, $resolution, $short_desc, $version)
{
    $ubirimiUser = User::getById($assigned_to);

    $bugzillaAssignedTo = getBugzillaUserByEmail($assigned_to);

    $query = "INSERT INTO bugs(
                alias, assigned_to, bug_file_loc, bug_severity, bug_status, cclist_accessible, component_id,
                creation_ts, deadline, delta_ts, estimated_time, everconfirmed, keywords, lastdiffed, op_sys,
                priority, product_id, qa_contact, remaining_time, rep_platform, reporter, reporter_accessible,
                resolution, short_desc, status_whiteboard, target_milestone, version, votes)
              VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    if ($stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query)) {
        $stmt->bind_param(
            "sissiissssissssiissiisssssi",
            null, $bugzillaAssignedTo[0]['user_id'], null, $bug_severity, $bug_status, 1, $component_id,
            $creation_ts, null, $date_updated, null, 0, null, null, '', $priority, $product_id, null, '0.00', 'All',
            $reporter, 1, $resolution, $short_desc, '', '---', $version, 0);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_array(MYSQLI_ASSOC);
        }

        return null;
    }
}

function getBugzillaUserByEmail($email)
{
    $query = "SELECT *
            FROM profiles
            where email = " . $email;


    $stmt = UbirimiContainer::get()['bugzilla.connection']->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}
