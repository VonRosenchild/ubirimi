<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\HelpDesk\Organization;
    use Ubirimi\Repository\HelpDesk\OrganizationCustomer;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientDomain = $session->get('client/company_domain');

    $organizationId = isset($_GET['id']) ? $_GET['id'] : null;

    $errors = array('empty_email' => false,
                    'email_not_valid' => false,
                    'email_already_exists' => false);

    $organizations = Organization::getByClientId($clientId);

    if (isset($_POST['confirm_new_customer'])) {

        $email = Util::cleanRegularInputField($_POST['email']);
        $firstName = Util::cleanRegularInputField($_POST['first_name']);
        $lastName = Util::cleanRegularInputField($_POST['last_name']);

        if (empty($email)) {
            $errors['empty_email'] = true;
        } else if (!Util::isValidEmail($email)) {
            $errors['email_not_valid'] = true;
        }

        $emailData = User::getUserByClientIdAndEmailAddress($clientId, mb_strtolower($email));
        if ($emailData)
            $errors['email_already_exists'] = true;

        if (Util::hasNoErrors($errors)) {
            $password = Util::randomPassword(8);

            $userId = UbirimiContainer::get()['user']->newUser(
                array(
                    'clientId' => $clientId,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'password' => $password,
                    'email' => $email,
                    'isCustomer' => true,
                    'clientDomain' => $session->get('client/company_domain')
                )
            );

            if ($organizationId) {
                OrganizationCustomer::create($organizationId, $userId);
                header('Location: /helpdesk/administration/customers/' . $organizationId);
            } else {
                header('Location: /helpdesk/administration/customers');
            }
        }
    }
    $menuSelectedCategory = 'general_user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Create User';

    require_once __DIR__ . '/../../../Resources/views/administration/customer/Add.php';