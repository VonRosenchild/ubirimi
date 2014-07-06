<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Issue\IssueComment;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap_cli.php';
require_once __DIR__. '/bugzilla/repository.php';
require_once __DIR__. '/bugzilla/connection.php';
require_once __DIR__ . '/bugzilla/bug_convertor.php';

/*
 * 1. install client record
 * 2. install products into projects
 * 3. install components per project
 * 4. install versions per project
 * 5. install users
 * 6. install bugs
 */

try {
    $clientData = array(
        'companyName' => 'Movidius',
        'companyDomain' => 'movidius',
        'baseURL' => 'http://movidius.ubirimi_net.lan',
        'companyEmail' => 'contact@movidius.ro',
        Client::INSTANCE_TYPE_ON_DEMAND,
        Util::getServerCurrentDateTime()
    );

    $valentinData = array(
        'firstName' => 'Vali',
        'lastName' => 'Muresan',
        'username' => 'vali',
        'password' => 'everythingisawesome',
        'email' => 'vali@vali.com'
    );

    UbirimiContainer::get()['db.connection']->autocommit(false);

//    dropAllTables();
//    insertMovidiusDatabase();

    /* delete old clients -- start */
//    foreach (Client::getAll() as $client) {
//        Client::deleteById($client['id']);
//    }
    /* delete old clients -- end */

    $clientId = 1959;
    $valiId = 1959;
    /* install client record Movidius -- end */

    /* install products into projects -- start */
    $movidiusProjects = getProducts($connectionBugzilla);
    $ubirimiProjects = Project::getByClientId($clientId);

//    foreach ($movidiusProjects as &$product) {
//        $projectId = installProject($clientId, $valiId, $product['name'], $product['description']);
//        $product['yongo_project_id'] = $projectId;
//    }
    /* install products into projects -- end */

    /* install components per project-- start */
    $movidiusComponents = getComponents($connectionBugzilla);
//    foreach ($movidiusComponents as $component) {
//        installComponent(
//            $valiId,
//            getYongoProjectFromMovidusProject($movidiusProjects, $component['product_id']),
//            $component['name'],
//            $component['description']
//        );
//    }
    /* install components per project-- end */

    /* install versions per project -- start */
    $movidiusVersions = getVersions($connectionBugzilla);
//    foreach ($movidiusVersions as &$version) {
//        installVersion(getYongoProjectFromMovidusProject($movidiusProjects, $version['product_id']), $version['value']);
//    }
    /* install versions per project -- end */

    /* install users -- start */
    $movidiusUsers = getUsers($connectionBugzilla);
    $ubirimiUsers = User::getByClientId($clientId);

//    foreach ($movidiusUsers as &$movidiusUser) {
//        $firstName = substr($movidiusUser['realname'], 0, strpos($movidiusUser['realname'], ' '));
//        $lastName = substr($movidiusUser['realname'], strpos($movidiusUser['realname'], ' ') + 1);
//
//        if (empty($movidiusUser['disabledtext'])) {
//            $userId = installUser(array(
//                'clientId' => $clientId,
//                'username' => $movidiusUser['login_name'],
//                'password' => 'everythingisawesome',
//                'firstName' => $firstName,
//                'lastName' => $lastName,
//                'email' => $movidiusUser['login_name'],
//                'clientDomain' => $clientData['companyDomain']
//            ));
//
//            $movidiusUser['yongo_user_id'] = $userId;
//        }
//    }
//    /* install users -- end */
//

    $ubirimiStatuses = getUbirimiStatuses($clientId);
    $ubirimiPriorities = getUbirimiPriorities($clientId);

    /* install bugs -- start */
    $movidiusBugs = getBugs($connectionBugzilla);
    foreach ($movidiusBugs as $bug) {
//        if (16005 == $bug['bug_id']) {
            $issue = Issue::addBugzilla(
                array(
                    'id' => getYongoProjectFromMovidusProject($movidiusProjects, $ubirimiProjects, $bug['product_id'])
                ),
                $bug['creation_ts'],
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

            $comments = getComments($connectionBugzilla, $bug['bug_id']);

            foreach ($comments as $comment) {
                $userId = getYongoUserFromMovidiusUsers($movidiusUsers, $ubirimiUsers, $comment['who']);

                if (null !== $userId) {
                    IssueComment::add(
                        $issue[0],
                        $userId,
                        $comment['thetext'],
                        $comment['bug_when']
                    );
                }
            }
//        }
    }
    /* install bugs -- end */

    UbirimiContainer::get()['db.connection']->commit();
} catch (Exception $e) {
    UbirimiContainer::get()['db.connection']->rollback();
    echo $e->getMessage();
}

UbirimiContainer::get()['db.connection']->autocommit(true);