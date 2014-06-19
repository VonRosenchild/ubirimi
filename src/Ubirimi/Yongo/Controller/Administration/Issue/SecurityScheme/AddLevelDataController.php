<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;

    Util::checkUserIsLoggedInAndRedirect();

    $levelId = $_GET['id'];

    $level = IssueSecurityScheme::getLevelById($levelId);
    $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($level['issue_security_scheme_id']);

    if ($issueSecurityScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $users = User::getByClientId($clientId);
    $groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $roles = PermissionRole::getByClient($clientId);

    if (isset($_POST['confirm_new_data'])) {

        $levelDataType = ($_POST['type']) ? $_POST['type'] : null;

        $user = $_POST['user'];
        $group = $_POST['group'];
        $role = $_POST['role'];
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        if ($levelDataType) {

            // check for duplicate information
            $duplication = false;
            $dataLevel = IssueSecurityScheme::getDataByLevelId($levelId);

            if ($dataLevel) {

                while ($data = $dataLevel->fetch_array(MYSQLI_ASSOC)) {
                    if ($data['group_id'] && $data['group_id'] == $group)
                        $duplication = true;
                    if ($data['user_id'] && $data['user_id'] == $user)
                        $duplication = true;
                    if ($data['permission_role_id'] && $data['permission_role_id'] && $role)
                        $duplication = true;

                    if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_PROJECT_LEAD)
                        if ($data['project_lead'])
                            $duplication = true;
                    if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_CURRENT_ASSIGNEE)
                        if ($data['current_assignee'])
                            $duplication = true;
                    if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_REPORTER)
                        if ($data['reporter'])
                            $duplication = true;
                }
            }
            if (!$duplication) {
                IssueSecurityScheme::addLevelData($levelId, $levelDataType, $user, $group, $role, $currentDate);
                Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Security Scheme Level ' . $level['name'], $currentDate);
            }
        }

        header('Location: /yongo/administration/issue-security-scheme-levels/' . $issueSecurityScheme['id']);
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme Level Data';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AddLevelData.php';