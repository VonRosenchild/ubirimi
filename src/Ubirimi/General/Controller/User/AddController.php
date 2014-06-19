<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use ubirimi\svn\SVNRepository;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);

    $clientDomain = $session->get('client/company_domain');

    $groupDevelopers = Group::getByName($clientId, 'Developers');

    $errors = array('empty_email' => false,
                    'email_not_valid' => false,
                    'empty_first_name' => false,
                    'empty_last_name' => false,
                    'email_already_exists' => false,
                    'empty_username' => false,
                    'empty_password' => false,
                    'password_mismatch' => false,
                    'invalid_username' => false,
                    'duplicate_username' => false);

    $svnRepoId = isset($_GET['fsvn']) ? $_GET['fsvn'] : null;

    if ($svnRepoId) {
        $svnRepo = SVNRepository::getById($svnRepoId);
        if ($svnRepo['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    if (isset($_POST['confirm_new_user'])) {

        $email = Util::cleanRegularInputField($_POST['email']);
        $firstName = Util::cleanRegularInputField($_POST['first_name']);
        $lastName = Util::cleanRegularInputField($_POST['last_name']);
        $username = Util::cleanRegularInputField($_POST['username']);
        $password = Util::cleanRegularInputField($_POST['password']);
        $passwordAgain = Util::cleanRegularInputField($_POST['password_again']);
        $svnRepoId = isset($_POST['fsvn']) ? Util::cleanRegularInputField($_POST['fsvn']) : null;

        if (empty($email))
            $errors['empty_email'] = true;
        else if (!Util::isValidEmail($email))
            $errors['email_not_valid'] = true;

        if (!Util::validateUsername($username))
            $errors['invalid_username'] = true;
        else {
            $existingUser = User::getByUsernameAndClientId($username, $clientId);

            if ($existingUser)
                $errors['duplicate_username'] = true;
        }

        $emailData = User::getUserByClientIdAndEmailAddress($clientId, mb_strtolower($email));
        if ($emailData)
            $errors['email_already_exists'] = true;

        if (empty($firstName))
            $errors['empty_first_name'] = true;

        if (empty($lastName))
            $errors['empty_last_name'] = true;

        if (empty($username))
            $errors['empty_username'] = true;

        if (empty($password))
            $errors['empty_password'] = true;

        if ($password != $passwordAgain)
            $errors['password_mismatch'] = true;

        if (Util::hasNoErrors($errors)) {
            $serviceData = array(
                'clientId' => $session->get('client/id'),
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'clientDomain' => $session->get('client/company_domain')
            );

            if ($svnRepoId) {
                $serviceData['svnRepoId'] = $svnRepoId;
                $serviceData['repositoryName'] = $svnRepo['name'];
            }

            UbirimiContainer::get()['user']->newUser($serviceData);

            if (!empty($svnRepoId)) {
                header('Location: /svn-hosting/administration/repository/users/' . $svnRepoId);
                exit(0);
            }

            header('Location: /general-settings/users');
        }
    }
    $menuSelectedCategory = 'general_user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Create User';

    require_once __DIR__ . '/../../Resources/views/user/Add.php';