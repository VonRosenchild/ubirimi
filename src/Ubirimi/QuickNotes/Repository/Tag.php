<?php

namespace Ubirimi\QuickNotes\Repository;

use Ubirimi\Container\UbirimiContainer;

class Tag
{
    public function getAll($userId)
    {
        $query = "select qn_tag.id, qn_tag.name, count(qn_notebook_note_tag.qn_notebook_note_id) as nr " .
            "from qn_tag " .
            "left join qn_notebook_note_tag on qn_notebook_note_tag.qn_tag_id = qn_tag.id " .
            "where qn_tag.user_id = ? " .
            "group by qn_tag.name " .
            "order by qn_tag.id asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByNameAndUserId($userId, $name, $tagId = null) {
        $query = 'select * ' .
            'from qn_tag ' .
            'where user_id = ? ' .
            'and LOWER(name) = ? ';

        if ($tagId)
            $query .= 'and id != ? ';

        $query .= 'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($tagId)
            $stmt->bind_param("isi", $userId, $name, $tagId);
        else
            $stmt->bind_param("is", $userId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByUserId($userId) {
        $query = "select qn_tag.* " .
            "from qn_tag " .
            "where qn_tag.user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function add($userId, $value, $date) {
        $query = "INSERT INTO qn_tag(user_id, name, date_created) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iss", $userId, $value, $date);
        $stmt->execute();

        $tagId = UbirimiContainer::get()['db.connection']->insert_id;

        return $tagId;
    }

    public function getById($tagId) {
        $query = "select * " .
            "from qn_tag " .
            "where id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else
            return null;
    }

    public function updateById($tagId, $name, $description, $date) {
        $query = 'UPDATE qn_tag SET name = ?, description = ?, date_updated = ? WHERE id = ? LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $tagId);
        $stmt->execute();
    }

    public function deleteById($tagId) {
        $query = 'delete from qn_tag where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();

        $query = 'delete from qn_notebook_note_tag where qn_tag_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
    }
}
