<?php

namespace Ubirimi\HelpDesk\Controller\Administration\Customer;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Organization\Customer;
use Ubirimi\HelpDesk\Repository\Organization\Organization;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Container\UbirimiContainer;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientDomain = $session->get('client/company_domain');

        $organizationId = $request->query->get('id');

        $errors = array(
            'empty_email' => false,
            'email_not_valid' => false,
            'email_already_exists' => false
        );

        $organizations = Organization::getByClientId($session->get('client/id'));

        if ($request->request->has('confirm_new_customer')) {
            $email = Util::cleanRegularInputField($request->request->get('email'));
            $firstName = Util::cleanRegularInputField($request->request->get('first_name'));
            $lastName = Util::cleanRegularInputField($request->request->get('last_name'));

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }

            $emailData = $this->getRepository('ubirimi.user.user')->getUserByClientIdAndEmailAddress(
                $session->get('client/id'),
                mb_strtolower($email)
            );

            if ($emailData)
                $errors['email_already_exists'] = true;

            if (Util::hasNoErrors($errors)) {
                $password = Util::randomPassword(8);

                $userId = UbirimiContainer::get()['user']->newUser(array(
                    'clientId' => $session->get('client/id'),
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'password' => $password,
                    'email' => $email,
                    'isCustomer' => true,
                    'clientDomain' => $session->get('client/company_domain')
                ));

                if ($organizationId) {
                    Customer::create($organizationId, $userId);
                    return new RedirectResponse('/helpdesk/administration/customers/' . $organizationId);
                }

                return new RedirectResponse('/helpdesk/administration/customers');
            }
        }
        $menuSelectedCategory = 'general_user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Create User';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/customer/Add.php', get_defined_vars());
    }
}
