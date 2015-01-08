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

namespace Ubirimi\SvnHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SvnHosting\Event\SvnHostingEvent;
use Ubirimi\SvnHosting\Event\SvnHostingEvents;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ChangePasswordController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'svn';
        $userId = $request->query->get('id', $request->request->get('id'));;
        $repoId = $request->query->get('repo_id', $request->request->get('repo_id'));

        $user = $this->getRepository(UbirimiUser::class)->getById($userId);
        $svnRepo = $this->getRepository(SvnRepository::class)->getById($repoId);

        $errors = array('empty_password' => false, 'password_mismatch' => false);

        if ($request->request->has('password') || $request->request->has('password_again')) {
            $password = Util::cleanRegularInputField($request->request->get('password'));
            $passwordAgain = Util::cleanRegularInputField($request->request->get('password_again'));

            if (empty($password)) {
                $errors['empty_password'] = true;
            }

            if ($password != $passwordAgain) {
                $errors['password_mismatch'] = true;
            }

            if (Util::hasNoErrors($errors)) {
                $this->getRepository(SvnRepository::class)->updateUserPassword($session->get('selected_svn_repo_id'), $userId, $password);

                $this->getRepository(SvnRepository::class)->updateHtpasswd($session->get('selected_svn_repo_id'), $session->get('client/company_domain'));
                $this->getRepository(SvnRepository::class)->updateAuthz();

                $svnEvent = new SvnHostingEvent($svnRepo['name'], $user, array('password' => $password));
                $this->getLogger()->addInfo(sprintf('SVN Change Password for [%s]', $svnRepo['name']), $this->getLoggerContext());

                UbirimiContainer::get()['dispatcher']->dispatch(SvnHostingEvents::PASSWORD_UPDATE, $svnEvent);

                return new Response('1');
            }
        }

        $menuSelectedCategory = 'svn';

        return $this->render(__DIR__ . '/../Resources/views/ChangePassword.php', get_defined_vars());
    }
}
