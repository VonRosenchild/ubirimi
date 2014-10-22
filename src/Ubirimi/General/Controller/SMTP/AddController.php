<?php

namespace Ubirimi\General\Controller\SMTP;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_mail';

        $emptyName = false;
        $emptyFromAddress = false;
        $emptyEmailPrefix = false;
        $emptyHostname = false;

        if ($request->request->has('add_smtp')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $fromAddress = Util::cleanRegularInputField($request->request->get('from_address'));
            $emailPrefix = Util::cleanRegularInputField($request->request->get('email_prefix'));
            $protocol = Util::cleanRegularInputField($request->request->get('protocol'));
            $hostname = Util::cleanRegularInputField($request->request->get('hostname'));
            $port = Util::cleanRegularInputField($request->request->get('port'));
            $timeout = Util::cleanRegularInputField($request->request->get('timeout'));
            $tls = $request->request->has('tls') ? 1 : 0;
            $username = Util::cleanRegularInputField($request->request->get('username'));
            $password = Util::cleanRegularInputField($request->request->get('password'));

            $date = Util::getServerCurrentDateTime();

            SMTPServer::add(
                $session->get('client/id'),
                $name,
                $description,
                $fromAddress,
                $emailPrefix,
                $protocol,
                $hostname,
                $port,
                $timeout,
                $tls,
                $username,
                $password,
                0,
                $date
            );

            $this->getRepository('ubirimi.general.log')->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS,
                $session->get('user/id'),
                'ADD SMTP Server ' . $name,
                $date
            );

            return new RedirectResponse('/general-settings/smtp-settings');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Create SMTP Server';

        return $this->render(__DIR__ . '/../../Resources/views/smtp/Add.php', get_defined_vars());
    }
}
