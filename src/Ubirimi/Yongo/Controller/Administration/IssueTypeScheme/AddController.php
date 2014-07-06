<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueType;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $type = $_GET['type'];
    if ($type == 'project') {
        $buttonLabel = 'Create Issue Type Scheme';
    } else {
        $buttonLabel = 'Create Workflow Issue Type Scheme';
    }

    $emptyIssueTypeName = false;
    $issueTypeExists = false;

    $allIssueTypes = IssueType::getAll($clientId);

    if (isset($_POST['new_type_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyIssueTypeName = true;
        }

        if (!$emptyIssueTypeName) {

            $issueTypeScheme = new IssueTypeScheme($clientId, $name, $description, $type);
            $currentDate = Util::getServerCurrentDateTime();
            $issueTypeSchemeId = $issueTypeScheme->save($currentDate);

            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 11) == 'issue_type_') {
                    $issueTypeId = str_replace('issue_type_', '', $key);
                    IssueTypeScheme::addData($issueTypeSchemeId, $issueTypeId, $currentDate);
                }
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Type Scheme ' . $name, $currentDate);

            if ($type == 'project') {
                header('Location: /yongo/administration/issue-type-schemes');
            } else {
                header('Location: /yongo/administration/workflows/issue-type-schemes');
            }
        }
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type Scheme';
    require_once __DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Add.php';