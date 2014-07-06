<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $session->set('selected_product_id', -1);

    $userId = isset($_GET['id']) ? $_GET['id'] : null;
    $location = isset($_GET['location']) ? $_GET['location'] : 'user_list';
    if ($userId) {
        $user = User::getById($userId);
        if ($user['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    $email = $user['email'];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $username = $user['username'];

    $errors = array('empty_email' => false,
                    'email_not_valid' => false,
                    'empty_username' => false,
                    'invalid_username' => false,
                    'duplicate_username' => false,
                    'empty_first_name' => false,
                    'empty_last_name' => false,
                    'email_already_exists' => false,
                    'at_least_one_administrator' => false);

    if (isset($_POST['confirm_update_user'])) {
        $userId = Util::cleanRegularInputField($_POST['user_id']);
        $email = Util::cleanRegularInputField($_POST['email']);
        $firstName = Util::cleanRegularInputField($_POST['first_name']);
        $lastName = Util::cleanRegularInputField($_POST['last_name']);
        $username = Util::cleanRegularInputField($_POST['username']);

        $clientAdministrators = Client::getAdministrators($clientId, $userId);

        $clientAdministratorFlag = 0;
        if (isset($_POST['client_administrator_flag'])) {
            $clientAdministratorFlag = Util::cleanRegularInputField($_POST['client_administrator_flag']);
        }
        $customerServiceDeskFlag = 0;
        if (isset($_POST['customer_service_desk_flag'])) {
            $customerServiceDeskFlag = Util::cleanRegularInputField($_POST['customer_service_desk_flag']);
        }

        if (!$clientAdministrators && $clientAdministratorFlag == 0) {
            $errors['at_least_one_administrator'] = true;
        } else if ($clientAdministratorFlag == 0 && $clientAdministrators && $clientAdministrators->num_rows == 0) {
            $errors['at_least_one_administrator'] = true;
        }

        if (empty($email)) {
            $errors['empty_email'] = true;
        } else if (!Util::isValidEmail($email)) {
            $errors['email_not_valid'] = true;
        }

        $emailData = Util::checkEmailAddressExistenceWithinClient(mb_strtolower($email), $userId, $clientId);

        if ($emailData)
            $errors['email_already_exists'] = true;

        if (empty($firstName))
            $errors['empty_first_name'] = true;

        if (empty($lastName))
            $errors['empty_last_name'] = true;

        if (empty($username))
            $errors['empty_username'] = true;

        if (!Util::validateUsername($username))
            $errors['invalid_username'] = true;
        else {
            $existingUser = User::getByUsernameAndClientId($username, $clientId, null, $userId);

            if ($existingUser)
                $errors['duplicate_username'] = true;
        }

        if (Util::hasNoErrors($errors)) {

            $currentDate = Util::getServerCurrentDateTime();
            User::updateById($userId, $firstName, $lastName, $email, $username, null, $clientAdministratorFlag, $customerServiceDeskFlag, $currentDate);
            $userUpdated = User::getById($userId);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $loggedInUserId, 'UPDATE User ' . $userUpdated['username'], $currentDate);

            if ($location == 'user_list')
                header('Location: /general-settings/users');
            else
                header('Location: /user/profile/' . $userId);
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Update User';

    $menuSelectedCategory = 'general_user';

    require_once __DIR__ . '/../../Resources/views/user/Edit.php';