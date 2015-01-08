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

namespace Ubirimi\SvnHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Event\UserEvent;
use ubirimi\svn\SVNUtils;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddRepositoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'svn';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);
        $isSVNAdministrator = $session->get('user/svn_administrator_flag');

        $emptyName = false;
        $duplicateName = false;
        $emptyCode = false;
        $duplicateCode = false;

        $clientId = $session->get('client/id');

        if ($request->request->has('confirm_new_svn_repository')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            if (empty($code)) {
                $emptyCode = true;
            } else {
                $svn_repository_exists = $this->getRepository(SvnRepository::class)->getByCode(mb_strtolower($code), $clientId);
                if ($svn_repository_exists) {
                    $duplicateCode = true;
                }
            }

            if (!$emptyName && !$emptyCode && !$duplicateName && !$duplicateCode) {
                $currentDate = Util::getServerCurrentDateTime();
                $repoId = $this->getRepository(SvnRepository::class)->addRepo($clientId, $session->get('user/id'), $name, $description, $code, $currentDate);

                $repoPath = UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($name);
                /* create the repository on disk */
                @mkdir(UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')), 0700, true);
                @mkdir(UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($name), 0700, true);

                try {
                    $this->getRepository(SvnRepository::class)->createSvn($repoPath);
                    SVNUtils::createStandardDirectories($repoPath);

                    /* add the user */
                    $this->getRepository(SvnRepository::class)->addUser($repoId, $session->get('user/id'));
                    $this->getRepository(SvnRepository::class)->updateUserPermissions($repoId, $session->get('user/id'), 1, 1);

                    /* apache config */
                    $this->getRepository(SvnRepository::class)->apacheConfig(Util::slugify($session->get('client/company_domain')), Util::slugify($name));
                } catch (\Exception $e) {

                }

                $userEvent = new UserEvent(
                    UserEvent::STATUS_NEW_SVN,
                    $session->get('user/first_name'),
                    $session->get('user/last_name'),
                    $session->get('user/username'),
                    null,
                    $session->get('user/email'),
                    array('svnRepoId' => $repoId, 'svnRepositoryName' => $name)
                );

                UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::USER, $userEvent);

                $this->getLogger()->addInfo('ADD SVN Repository ' . Util::slugify($name), $this->getLoggerContext());

                return new RedirectResponse('/svn-hosting/administration/all-repositories');
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/AddRepository.php', get_defined_vars());
    }
}
