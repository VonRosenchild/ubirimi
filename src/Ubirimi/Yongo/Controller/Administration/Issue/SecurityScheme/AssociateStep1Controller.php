<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    $menuSelectedCategory = 'project';
    $issueSecuritySchemes = IssueSecurityScheme::getByClientId($clientId);

    if (isset($_POST['cancel'])) {
        header('Location: /yongo/administration/project/issue-security/' . $projectId);
    } elseif (isset($_POST['next'])) {
        $schemeId = $_POST['scheme'];
        header('Location: /yongo/administration/project/associate-issue-security-level/' . $projectId . '/' . $schemeId);
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Associate Issue Security';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AssociateStep1.php';
