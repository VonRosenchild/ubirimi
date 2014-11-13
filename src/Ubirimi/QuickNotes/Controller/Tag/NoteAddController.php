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

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\QuickNotes\Repository\Note;

class NoteAddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $date = Util::getServerCurrentDateTime();

        $value = $request->request->get('value');
        $noteId = $request->request->get('id');

        // check for duplicates in the user space
        $tagUserExists = $this->getRepository(Tag::class)->getByNameAndUserId($session->get('user/id'), mb_strtolower($value));

        if ($tagUserExists) {
            // check if it is already added to the note
            $tagNoteExists = $this->getRepository(Note::class)->getTagByTagIdAndNoteId($noteId, $tagUserExists['id']);
            if (!$tagNoteExists) {
                $this->getRepository(Note::class)->addTag($noteId, $tagUserExists['id'], $date);

                return new Response('1');
            }

            return new Response('0');
        }

        $tagId = $this->getRepository(Tag::class)->add($session->get('user/id'), $value, $date);
        $this->getRepository(Note::class)->addTag($noteId, $tagId, $date);

        return new Response('1');
    }
}
