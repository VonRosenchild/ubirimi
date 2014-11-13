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

namespace Ubirimi\QuickNotes\Repository;

use Ubirimi\Container\UbirimiContainer;

class Note
{
    public function getById($noteId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook_note " .
            "where qn_notebook_note.id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $noteId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function updateById($noteId, $content, $date) {
        $query = 'UPDATE qn_notebook_note SET content = ?, date_updated = ? WHERE id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $content, $date, $noteId);
        $stmt->execute();
    }

    public function deleteById($noteId) {
        $query = 'delete from qn_notebook_note where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $noteId);
        $stmt->execute();

        $query = 'delete from qn_notebook_note_tag where qn_notebook_note_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $noteId);
        $stmt->execute();
    }

    public function getPreviousNoteInNotebook($notebookId, $noteId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook_note " .
            "where qn_notebook_note.id < ? and qn_notebook_id = ? " .
            "order by id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $noteId, $notebookId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getFollowingNoteInNotebook($notebookId, $noteId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook_note " .
            "where qn_notebook_note.id > ? and qn_notebook_id = ? " .
            "order by id asc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $noteId, $notebookId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getFirstByNotebookId($notebookId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook_note " .
            "where qn_notebook_id = ? " .
            "order by id asc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $notebookId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getAllByUserId($userId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook " .
            "left join qn_notebook_note on qn_notebook_note.qn_notebook_id = qn_notebook.id " .
            "where qn_notebook.user_id = ? and qn_notebook_note.id is not null " .
            "order by qn_notebook_note.id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $notesArray = array();
            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                $notesArray[] = $data;
            }
            return $notesArray;
        } else
            return null;
    }

    public function addTag($noteId, $tagId, $date) {
        $query = "INSERT INTO qn_notebook_note_tag(qn_notebook_note_id, qn_tag_id, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iis", $noteId, $tagId, $date);
        $stmt->execute();

        $noteTagId = UbirimiContainer::get()['db.connection']->insert_id;

        return $noteTagId;
    }

    public function getTags($noteId) {
        $query = "select qn_tag.id, qn_tag.name " .
            "from qn_notebook_note_tag " .
            "left join qn_tag on qn_tag.id = qn_notebook_note_tag.qn_tag_id " .
            "where qn_notebook_note_tag.qn_notebook_note_id = ? " .
            "order by qn_tag.id asc ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $noteId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getTagByNoteIdAndName($noteId, $userId, $value) {
        $query = "select qn_tag.* " .
            "from qn_tag " .
            "where user_id = ? and name = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $userId, $value);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getFirstNoteByTagId($userId = null, $tagId) {
        $query = "select qn_notebook_note.* " .
            "from qn_notebook " .
            "left join qn_notebook_note on qn_notebook_note.qn_notebook_id = qn_notebook.id " .
            "left join qn_notebook_note_tag on qn_notebook_note_tag.qn_notebook_note_id = qn_notebook_note.id " .
            "where " .
            "qn_notebook.user_id = " . $userId . " " .
            "and qn_notebook_note_tag.qn_tag_id = ? " .
            "order by qn_notebook_note.id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function getTagByTagIdAndNoteId($noteId, $tagId) {
        $query = "select qn_notebook_note_tag.qn_tag_id " .
            "from qn_notebook_note_tag " .
            "where qn_notebook_note_tag.qn_notebook_note_id = ? and qn_tag_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $noteId, $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function move($noteId, $targetNotebookId) {
        $query = 'UPDATE qn_notebook_note SET qn_notebook_id = ? WHERE id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $targetNotebookId, $noteId);
        $stmt->execute();
    }

    public function deleteTagById($noteId, $tagId) {
        $query = 'delete from qn_notebook_note_tag WHERE qn_notebook_note_id = ? and qn_tag_id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $noteId, $tagId);
        $stmt->execute();
    }

    public function updateTitleById($id, $summary) {
        $query = 'UPDATE qn_notebook_note SET summary = ? WHERE id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $summary, $id);
        $stmt->execute();
    }
}