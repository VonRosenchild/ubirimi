<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $fieldConfigurations = FieldConfigurationScheme::getFieldConfigurations($project['issue_type_field_configuration_id']);
    $allFields = Field::getByClient($clientId);
    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Type Field Configuration';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/ViewIssueTypeFieldConfiguration.php';