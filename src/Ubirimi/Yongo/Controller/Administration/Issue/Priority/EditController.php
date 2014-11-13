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

namespace Ubirimi\Yongo\Controller\Administration\Issue\Priority;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->get('id');
        $issuePriority = $this->getRepository(IssueSettings::class)->getById($Id, 'priority');

        if ($issuePriority['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $priorityExists = false;

        if ($request->request->has('edit_priority')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = $request->request->get('color');

            if (empty($name))
                $emptyName = true;

            // check for duplication
            $priority = $this->getRepository(IssueSettings::class)->getByName(
                $session->get('client/id'),
                'priority',
                mb_strtolower($name),
                $Id
            );

            if ($priority)
                $priorityExists = true;

            if (!$priorityExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(IssueSettings::class)->updateById($Id, 'priority', $name, $description, $color, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Priority ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/priorities');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Priority';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/priority/Edit.php', get_defined_vars());
    }
}
