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


class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyPriorityName = false;
        $priorityExists = false;

        if ($request->request->has('new_priority')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = Util::cleanRegularInputField($request->request->get('color'));

            if (empty($name))
                $emptyPriorityName = true;

            // check for duplication
            $priority = $this->getRepository(IssueSettings::class)->getByName($session->get('client/id'), 'priority', mb_strtolower($name));
            if ($priority)
                $priorityExists = true;

            if (!$priorityExists && !$emptyPriorityName) {
                $iconName = 'generic.png';
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(IssueSettings::class)->create(
                    'issue_priority',
                    $session->get('client/id'),
                    $name,
                    $description,
                    $iconName,
                    $color,
                    $currentDate
                );

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Priority ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue/priorities');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Priority';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/priority/Add.php', get_defined_vars());
    }
}
