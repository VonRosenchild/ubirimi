<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $projectId = $_GET['id'];
    $schemeId = $_GET['scheme_id'];
    $selectedScheme = IssueSecurityScheme::getMetaDataById($schemeId);
    $project = Project::getById($projectId);

    $projectIssueSecuritySchemeId = $project['issue_security_scheme_id'];
    $projectIssueSecurityScheme = null;
    if ($projectIssueSecuritySchemeId)
        $projectIssueSecurityScheme = IssueSecurityScheme::getMetaDataById($projectIssueSecuritySchemeId);

    $menuSelectedCategory = 'project';
    $selectedSchemeLevels = IssueSecurityScheme::getLevelsByIssueSecuritySchemeId($schemeId);

    if (isset($_POST['cancel'])) {
        header('Location: /yongo/administration/project/issue-security/' . $projectId);
    } elseif (isset($_POST['associate'])) {
        $oldNewLevel = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 10) == 'new_level_') {
                $newSecurityLevel = $_POST[$key];
                $oldSecurityLevel = str_replace('new_level_', '', $key);
                if ($oldSecurityLevel == 0) {
                    Project::updateAllIssuesSecurityLevel($projectId, $newSecurityLevel);
                } else {
                    $oldNewLevel[] = array($oldSecurityLevel, $newSecurityLevel);
                }
            } else if ($key == 'no_level_set') {
                $newSecurityLevel = $_POST[$key];
                Project::updateIssueSecurityLevelForUnsercuredIssues($projectId, $newSecurityLevel);
            }
        }

        if (count($oldNewLevel)) {
            $date = Util::getServerCurrentDateTime();
            Project::updateIssuesSecurityLevel($projectId, $oldNewLevel, $date);
        }

        Project::setIssueSecuritySchemeId($projectId, $schemeId);
        header('Location: /yongo/administration/project/issue-security/' . $projectId);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Associate Issue Security';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AssociateStep2.php';