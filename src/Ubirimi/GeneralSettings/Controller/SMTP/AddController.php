<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\General\Controller\SMTP;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
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

            $this->getRepository(SMTPServer::class)->add(
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

            $this->getLogger()->addInfo('ADD SMTP Server ' . $name, $this->getLoggerContext());

            return new RedirectResponse('/general-settings/smtp-settings');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Create SMTP Server';

        return $this->render(__DIR__ . '/../../Resources/views/smtp/Add.php', get_defined_vars());
    }
}
