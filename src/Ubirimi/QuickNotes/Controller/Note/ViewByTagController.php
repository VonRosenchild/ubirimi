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

class ViewByTagController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');
        $viewType = $request->get('view_type');
        $menuSelectedCategory = 'notebooks';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Quick Notes';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

        $noteId = $request->get('note_id');
        $tagId = $request->get('tag_id');

        if (-1 == $tagId) {
            $tagId = null;
        }

        $note = $this->getRepository(Note::class)->getById($noteId);
        $notebookId = $note['qn_notebook_id'];
        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'), 'array');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);
        $notes = $this->getRepository(Notebook::class)->getNotesByTagId($session->get('user/id'), $tagId, 'array');

        $allTags = $this->getRepository(Tag::class)->getAll($session->get('user/id'));
        $tags = $this->getRepository(Note::class)->getTags($noteId);

        return $this->render(__DIR__ . '/../../Resources/views/Note/View.php', get_defined_vars());
    }
}
