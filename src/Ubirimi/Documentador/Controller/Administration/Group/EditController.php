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

namespace Ubirimi\Documentador\Controller\Administration\Group;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $Id = $request->get('id');
        $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($Id);

        if ($group['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $name = $group['name'];
        $description = $group['description'];

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('update_group')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }
            if (!$emptyName) {
                $groupAlreadyExists = $this->getRepository(UbirimiGroup::class)->getByNameAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO, mb_strtolower($name), $Id);

                if ($groupAlreadyExists) {
                    $duplicateName = true;
                }
            }

            if (!$emptyName && !$duplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(UbirimiGroup::class)->updateById($Id, $name, $description, $currentDate);

                return new RedirectResponse('/documentador/administration/groups');
            }
        }
        $menuSelectedCategory = 'doc_users';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/group/Edit.php', get_defined_vars());
    }
}