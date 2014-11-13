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

namespace Ubirimi\QuickNotes\Controller\Notebook;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\SystemProduct;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notebookId = $request->get('id');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);

        if ($notebook['user_id'] != $session->get('user/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $notebookExists = false;

        if ($request->request->has('edit_notebook')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            // check for duplication

            $notebookDuplicate = $this->getRepository(Notebook::class)->getByName(
                $session->get('user/id'),
                mb_strtolower($name),
                $notebookId
            );

            if ($notebookDuplicate) {
                $notebookExists = true;
            }

            if (!$notebookExists && !$emptyName) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository(Notebook::class)->updateById($notebookId, $name, $description, $date);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_QUICK_NOTES,
                    $session->get('user/id'),
                    'UPDATE NOTEBOOK notebook ' . $name,
                    $date
                );

                return new RedirectResponse('/quick-notes/my-notebooks');
            }
        }

        $menuSelectedCategory = 'notebooks';

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME
            . ' / Notebook: ' . $notebook['name']
            . ' / Update';

        return $this->render(__DIR__ . '/../../Resources/views/Notebook/Edit.php', get_defined_vars());
    }
}
