<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'project';
    $issueSecurityScheme = null;
    if ($project['issue_security_scheme_id']) {
        $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($project['issue_security_scheme_id']);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Security Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/project/ViewIssueSecurityScheme.php';
