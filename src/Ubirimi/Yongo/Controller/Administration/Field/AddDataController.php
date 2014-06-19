<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\CustomField;
    use Ubirimi\Yongo\Repository\Field\FieldType;
    use Ubirimi\Yongo\Repository\Issue\IssueType;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypes = IssueType::getAll($clientId);
    $projects = Project::getByClientId($clientId);

    $fieldTypeCode = $_GET['type'];

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['finish_custom_field'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $issueType = $_POST['issue_type'];
        $project = $_POST['project'];

        $fieldType = FieldType::getByCode($fieldTypeCode);
        $fieldTypeId = $fieldType['id'];

        if (empty($name)) {
            $emptyName = true;
        } else {
            // check for duplicate name

            $duplicateField = CustomField::getByNameAndType($clientId, $name, $fieldTypeId);
            if ($duplicateField)
                $duplicateName = true;
        }
        if (!$emptyName && !$duplicateName) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $fieldId = CustomField::create($clientId, $fieldTypeCode, $name, $description, $issueType, $project, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Custom Field ' . $name, $date);

            header('Location: /yongo/administration/custom-field/edit-field-screen/' . $fieldId);
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Custom Field Data';

    require_once __DIR__ . '/../../../Resources/views/administration/field/AddData.php';