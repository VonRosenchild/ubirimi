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

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityType;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $emptySpaceName = false;
        $emptySpaceCode = false;
        $doubleCode = false;
        $doubleName = false;

        if ($request->request->has('add_space')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name)) {
                $emptySpaceName = true;
            }

            if (empty($code)) {
                $emptySpaceCode = true;
            }

            if (!$emptySpaceName && !$emptySpaceCode) {
                $doubleSpace = $this->getRepository(Space::class)->getByCodeAndClientId($clientId, $code);
                if ($doubleSpace) {
                    $doubleCode = true;
                }
                $doubleSpace = $this->getRepository(Space::class)->getByNameAndClientId($clientId, $name);
                if ($doubleSpace) {
                    $doubleName = true;
                }
                if (!$doubleCode && !$doubleName) {

                    $date = Util::getServerCurrentDateTime();

                    $space = new Space($clientId, $loggedInUserId, $name, $code, $description);
                    $spaceId = $space->save($currentDate);

                    $this->getLogger()->addInfo('ADD Documentador space ' . $name, $this->getLoggerContext());

                    // set space permission for current user
                    $this->getRepository(Space::class)->addUserAllPermissions($spaceId, $loggedInUserId);

                    // set default home page
                    $content = '<p><span style="font-size:24px"><strong>Welcome to your new space!</strong></span></p><div class="message-content" style="font-family: Arial, sans-serif; font-size: 14px;"><p>Documentador spaces are great for sharing content and news with your team. This is your home page. You can customize this page in anyway you like.</p></div>';
                    $page = new Entity(EntityType::ENTITY_BLANK_PAGE, $spaceId, $loggedInUserId, null, $name . ' Home', $content);
                    $pageId = $page->save($currentDate);
                    $this->getRepository(Space::class)->setHomePageId($spaceId, $pageId);

                    // add space permissions for groups
                    $this->getRepository(Space::class)->setDefaultPermissions($clientId, $spaceId);

                    return new RedirectResponse('/documentador/administration/spaces');
                }
            }
        }

        $menuSelectedCategory = 'doc_spaces';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/space/Add.php', get_defined_vars());
    }
}