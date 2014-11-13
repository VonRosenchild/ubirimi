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

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\SystemProduct;

class ViewAllController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'notes';

        $viewType = $request->get('view_type');

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Quick Notes';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

        $notebookId = -1;
        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'), 'array');
        $notes = $this->getRepository(Note::class)->getAllByUserId($session->get('user/id'));

        $notebook = null;
        if ($notes) {
            $tags = $this->getRepository(Note::class)->getTags($notes[0]['id']);
            $noteId = $notes[0]['id'];
            $note = $notes[0];
            $notes = $this->getRepository(Notebook::class)->getNotesByNotebookId($notebookId, $session->get('user/id'), null, 'array');
        } else {
            $note = null;
        }

        $allTags = $this->getRepository(Tag::class)->getAll($session->get('user/id'));

        return $this->render(__DIR__ . '/../../Resources/views/Note/View.php', get_defined_vars());
    }
}
