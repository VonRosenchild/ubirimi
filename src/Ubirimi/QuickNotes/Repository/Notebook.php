<?php

    namespace Ubirimi\QuickNotes\Repository;

    use Ubirimi\Container\UbirimiContainer;

    class Notebook {

        public static function getByUserId($userId, $resultType = null) {
            $query = "select qn_notebook.id, qn_notebook.default_flag, qn_notebook.name, qn_notebook.description, qn_notebook.date_created " .
                "from qn_notebook " .
                "left join user on user.id = qn_notebook.user_id " .
                "where qn_notebook.user_id = ?";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    if ($resultType == 'array') {
                        $resultArray = array();
                        while ($board = $result->fetch_array(MYSQLI_ASSOC)) {
                            $resultArray[] = $board;
                        }

                        return $resultArray;
                    } else
                        return $result;
                } else
                    return null;
            }
        }

        public static function getDefaultByUserId($userId) {
            $query = "select qn_notebook.* " .
                "from qn_notebook " .
                "where user_id = ? and default_flag = 1 " .
                "order by id asc";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    return $result->fetch_array(MYSQLI_ASSOC);
                } else
                    return null;
            }
        }

        public static function getNotesByNotebookId($notebookId, $userId = null, $tagId = null, $resultType = null) {
            $query = "select qn_notebook_note.* " .
                "from qn_notebook_note " .
                "left join qn_notebook_note_tag on qn_notebook_note_tag.qn_notebook_note_id = qn_notebook_note.id ";

            if (-1 == $notebookId) {
                $query .= 'left join qn_notebook on qn_notebook.id = qn_notebook_note.qn_notebook_id ';
                $query .= "where qn_notebook.user_id = " . $userId . ' ';
            } else {
                $query .= "where qn_notebook_note.qn_notebook_id = " . $notebookId . ' ';
            }

            if ($tagId) {
                $query .= 'and qn_notebook_note_tag.qn_tag_id = ' . $tagId . ' ';
            }

            $query .= 'group by qn_notebook_note.id ';
            $query .= "order by id asc";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    if ($resultType == 'array') {
                        $resultArray = array();
                        while ($board = $result->fetch_array(MYSQLI_ASSOC)) {
                            $resultArray[] = $board;
                        }

                        return $resultArray;
                    } else
                        return $result;
                } else
                    return null;
            }
        }

        public static function getNotesByTagId($userId, $tagId, $resultType = null) {
            $query = "select qn_notebook_note.* " .
                "from qn_notebook_note " .
                "left join qn_notebook_note_tag on qn_notebook_note_tag.qn_notebook_note_id = qn_notebook_note.id " .
                'left join qn_notebook on qn_notebook.id = qn_notebook_note.qn_notebook_id ' .
                "where qn_notebook.user_id = " . $userId . ' ';

            if ($tagId) {
                $query .= 'and qn_notebook_note_tag.qn_tag_id = ' . $tagId . ' ';
            }

            $query .= 'group by qn_notebook_note.id ';
            $query .= "order by id asc";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    if ($resultType == 'array') {
                        $resultArray = array();
                        while ($board = $result->fetch_array(MYSQLI_ASSOC)) {
                            $resultArray[] = $board;
                        }

                        return $resultArray;
                    } else
                        return $result;
                } else
                    return null;
            }
        }


        public static function save($userId, $name, $description, $date) {
            $query = "INSERT INTO qn_notebook(user_id, name, description, date_created) VALUES (?, ?, ?, ?)";
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("isss", $userId, $name, $description, $date);
                $stmt->execute();

                $notebookId = UbirimiContainer::get()['db.connection']->insert_id;

                return $notebookId;
            }

            return false;
        }

        public static function getById($notebookId) {
            $query = "select qn_notebook.id, qn_notebook.user_id, qn_notebook.name, qn_notebook.description, " .
                "qn_notebook.date_created, qn_notebook.date_updated, " .
                "user.client_id " .
                "from qn_notebook " .
                "left join user on user.id = qn_notebook.user_id " .
                "where qn_notebook.id = ? " .
                "limit 1";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $notebookId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows) {
                    return $result->fetch_array(MYSQLI_ASSOC);
                } else
                    return null;
            } else {
                return null;
            }
        }

        public static function getByName($userId, $name, $notebookId = null) {
            $query = 'select id, name, description ' .
                'from qn_notebook ' .
                'where user_id = ? ' .
                'and LOWER(name) = ? ';

            if ($notebookId)
                $query .= 'and id != ? ';

            $query .= 'limit 1';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                if ($notebookId)
                    $stmt->bind_param("isi", $userId, $name, $notebookId);
                else
                    $stmt->bind_param("is", $userId, $name);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result->fetch_array(MYSQLI_ASSOC);
                else
                    return null;
            } else {
                return null;
            }
        }

        public static function updateById($notebookId, $name, $description, $date) {
            $query = 'UPDATE qn_notebook SET name = ?, description = ?, date_updated = ? WHERE id = ? LIMIT 1';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("sssi", $name, $description, $date, $notebookId);
                $stmt->execute();
            }
        }

        public static function deleteById($notebookId) {
            $query = 'delete from qn_notebook_note where qn_notebook_id = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $notebookId);
                $stmt->execute();
            }

            $query = 'delete from qn_notebook where id = ? limit 1';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $notebookId);
                $stmt->execute();
            }
        }

        public static function deleteByUserId($userId) {
            $notebooks = Notebook::getByUserId($userId);

            while ($notebooks && $notebook = $notebooks->fetch_array(MYSQLI_ASSOC)) {
                Notebook::deleteById($notebooks['id']);
            }
        }

        public static function addNote($notebookId, $date) {
            $query = "INSERT INTO qn_notebook_note(qn_notebook_id, summary, date_created) VALUES (?, ?, ?)";
            $summary = 'Untitled';
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

                $stmt->bind_param("iss", $notebookId, $summary, $date);
                $stmt->execute();

                $noteId = UbirimiContainer::get()['db.connection']->insert_id;

                return $noteId;
            }

            return false;
        }
    }