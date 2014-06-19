<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }
    $fieldConfigurationSchemes = FieldConfigurationScheme::getByClient($clientId);

    $menuSelectedCategory = 'project';

    if (isset($_POST['associate'])) {

        $issueTypeFieldSchemeId = $_POST['issue_type_field_scheme'];
        Project::updateFieldConfigurationScheme($projectId, $issueTypeFieldSchemeId);

        header('Location: /yongo/administration/project/fields/' . $projectId);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Issue Type Field Configuration';
    require_once __DIR__ . '/../../../../Resources/views/administration/project/SelectIssueTypeFieldScheme.php';