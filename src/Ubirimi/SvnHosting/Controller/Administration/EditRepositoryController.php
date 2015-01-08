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
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditRepositoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $repoId = Util::cleanRegularInputField($request->get('id'));

        $menuSelectedCategory = 'svn';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $emptyCode = false;
        $duplicateCode = false;

        if ($request->request->has('confirm_edit_svn_repository')) {

            $repoId = Util::cleanRegularInputField($request->request->get('repo_id'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($code)) {
                $emptyCode = true;
            } else {
                $svn_repository_exists = $this->getRepository(SvnRepository::class)->getByCode(mb_strtolower($code), $clientId, $repoId);
                if ($svn_repository_exists) {
                    $duplicateCode = true;
                }
            }

            if (!$emptyCode && !$duplicateCode) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository(SvnRepository::class)->updateRepo($description, $code, $repoId, $date);

                $this->getLogger()->addInfo('UPDATE SVN Repository ' . Util::slugify($code), $this->getLoggerContext());

                return new RedirectResponse('/svn-hosting/administration/all-repositories');
            }
        } else {
            $svnRepo = $this->getRepository(SvnRepository::class)->getById($request->get('id'));

            if ($svnRepo['client_id'] != $clientId) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }

            $name = $svnRepo['name'];
            $code = $svnRepo['code'];
            $description = $svnRepo['description'];
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/EditRepository.php', get_defined_vars());
    }
}
