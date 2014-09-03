<?php

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
        $tagUserExists = Tag::getByNameAndUserId($session->get('user/id'), mb_strtolower($value));

        if ($tagUserExists) {
            // check if it is already added to the note
            $tagNoteExists = Note::getTagByTagIdAndNoteId($noteId, $tagUserExists['id']);
            if (!$tagNoteExists) {
                Note::addTag($noteId, $tagUserExists['id'], $date);

                return new Response('1');
            }

            return new Response('0');
        }

        $tagId = Tag::add($session->get('user/id'), $value, $date);
        Note::addTag($noteId, $tagId, $date);

        return new Response('1');
    }
}
