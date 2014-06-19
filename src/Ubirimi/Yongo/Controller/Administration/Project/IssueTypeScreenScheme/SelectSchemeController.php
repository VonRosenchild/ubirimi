<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }
    $issueTypeScreenSchemes = IssueTypeScreenScheme::getByClientId($clientId);

    $menuSelectedCategory = 'project';

    if (isset($_POST['associate'])) {

        $issueTypeScreenSchemeId = $_POST['issue_type_screen_scheme'];
        Project::updateIssueTypeScreenScheme($projectId, $issueTypeScreenSchemeId);

        header('Location: /yongo/administration/project/screens/' . $projectId);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Issue Screen Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/project/SelectIssueTypeScreenScheme.php';