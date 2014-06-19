<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../include/header.php';

    Util::checkUserIsLoggedInAndRedirect();

    $settings = Client::getYongoSettings($clientId);
    $menuSelectedCategory = 'system';

    $projects = Client::getProjects($clientId);

    if (isset($_POST['start_import'])) {
        $organization = $_POST['organization'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $projectId = $_POST['project'];
        $repository = $_POST['repository'];

        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        $curl = curl_init();

        // import the milestones as Yongo versions
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_URL => 'https://api.github.com/repos/' . $username . '/' . $repository . '/milestones',
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $result = curl_exec($curl);

        $milestonesArray = json_decode($result, true);
        if ($milestonesArray) {
            foreach ($milestonesArray as $milestone) {
                $milestoneName = $milestone['title'];
                $milestoneDescription = $milestone['description'];

                $versionExists = Project::getVersionByName($projectId, $milestoneName);
                if (!$versionExists) {
                    // add the version
                    Project::addVersion($projectId, $milestoneName, $milestoneDescription, $date);
                }
            }
        }

        // import the users
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_URL => 'https://api.github.com/orgs/' . $organization . '/members',
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $result = curl_exec($curl);

        $usersArray = json_decode($result, true);
        if ($usersArray) {
            foreach ($usersArray as $user) {
                $login = $user['login'];
                $userExists = User::getByUsernameAndClientId($login, $clientId);
                if (!$userExists) {
                    $firstName = $user['name'];
                    $email = $user['email'];

                    $userData = User::add($clientId, $firstName, '', $email, $login, $login, 20, $date);
                }
            }
        }

        // import the issues
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_URL => 'https://api.github.com/repos/' . $username . '/' . $repository . '/issues',
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $result = curl_exec($curl);

        $issuesGithubArray = json_decode($result, true);

        $issuePriorities = IssueSettings::getAllIssueSettings('priority', $clientId, 'array');
        $issueTypes = IssueSettings::getAllIssueSettings('type', $clientId, 'array');
        $issueStatuses = IssueSettings::getAllIssueSettings('status', $clientId, 'array');

        $defaultIssuePriorityId = $issuePriorities[0]['id'];
        $defaultIssueTypeId = $issueTypes[0]['id'];
        $defaultIssueStatusId = $issueStatuses[0]['id'];

        foreach ($issuesGithubArray as $issue) {

            // find the reporter user of the issue
            $userGithubCreated = $issue['user']['login'];
            $user = User::getByUsernameAndClientId($userGithubCreated, $clientId);
            $userReporterId = $user['id'];

            // find the assignee of the issue
            $userGithubAssignee = $issue['assignee'];
            $userAssignedId = null;
            if ($userGithubAssignee) {
                $assigneeGithubUsername = $issue['assignee']['login'];
                $user = User::getByUsernameAndClientId($assigneeGithubUsername, $clientId);
                $userAssignedId = $user['id'];
            }
            $issueSystemFields = array('priority' => $defaultIssuePriorityId, 'type' => $defaultIssueTypeId, 'status' => $defaultIssueStatusId,
                                       'assignee' => $userAssignedId, 'reporter' => $userReporterId, 'summary' => $issue['title'],
                                       'description' => $issue['body'], 'environment' => null, 'due_date' => null);

            $issueData = Issue::addRaw($projectId, $date, $issueSystemFields);
            $newIssueId = $issueData[0];

            // set issue versions if any
            if ($issue['milestone']) {
                $projectVersionData = Project::getVersionByName($projectId, $issue['milestone']['title']);
                $projectVersionId = $projectVersionData['id'];
                Issue::setAffectedVersion($newIssueId, $projectVersionId);
            }

            // set issue comments
            if ($issue['comments']) {
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_USERPWD => $username . ":" . $password,
                    CURLOPT_URL => 'https://api.github.com/repos/' . $username . '/' . $repository . '/issues/' . $issue['number'] . '/comments',
                    CURLOPT_SSL_VERIFYPEER => false
                ));

                $result = curl_exec($curl);

                $issueCommentsGithubArray = json_decode($result, true);
                foreach ($issueCommentsGithubArray as $issueComment) {
                    $userCreatedUsername = $issueComment['user']['login'];
                    $userCommentId = User::getByUsernameAndClientId($userCreatedUsername, $clientId, 'id');

                    Issue::addComment($newIssueId, $userCommentId, $issueComment['body'], str_replace(array('T', 'Z'), ' ', $issueComment['created_at']));
                }
            }

            // set issue attachments

            // if the content of the issue contains attachments add them

            $bodyIssue = $issue['body'];
            $countMatches = preg_match_all('/(\!\[clipboard\]\(.*\))/', $bodyIssue, $resultMatches);
            if ($countMatches) {
                // create the folder
                $attachmentsFolder = './../a/attachments/' . $newIssueId;
                mkdir($attachmentsFolder);
                $path = '/attachments/' . $newIssueId;
                $attachmentsGit = $countMatches[1];
                for ($k = 0; $k < count($attachmentsGit); $k++) {
                    // download the file
                    $attachmentExtension = substr($attachmentsGit[$k], strlen($attachmentsGit[$k] - 4), 3);
                    $attachmentName = 'Attachment_' . ($k + 1) . '.' . $attachmentExtension;
                    $fileURL = substr($attachmentsGit[$k], 13, strlen($attachmentsGit[$k]) - 4);
                    file_put_contents('./..' . $attachmentsFolder . '/' . $attachmentName, fopen("http://someurl/file.zip", 'r'));

//                    Issue::addAttachment($newIssueId, $path, $attachmentName,  )
                }
            }
        }

        curl_close($curl);
    }
?>

<body>
    <?php require_once __DIR__ . '/../include/menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">Ubirimi Import > From Github</div>
                </td>
            </tr>
        </table>

        <form name="github-import" method="post" action="/yongo/import-from-github">
            <table width="100%">
                <tr>
                    <td width="200px">Organization</td>
                    <td>
                        <input type="text" value="ubirimi" name="organization" class="inputText" />
                    </td>
                </tr>
                <tr>
                    <td width="200px">Repository</td>
                    <td>
                        <input type="text" value="repo1" name="repository" class="inputText" />
                    </td>
                </tr>
                <tr>
                    <td>Account Username</td>
                    <td>
                        <input class="inputText" type="text" value="domnulnopcea" name="username" />
                    </td>
                </tr>
                <tr>
                    <td>Account Password</td>
                    <td>
                        <input type="password" value="cristinasinaomi1" name="password" class="inputText" />
                    </td>
                </tr>
                <tr>
                    <td>Destination Project</td>
                    <td>
                        <select name="project" class="inputTextCombo">
                            <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="start_import" class="btn ubirimi-btn">Start Import</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/issue/statuses">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../include/footer.php' ?>
</body>