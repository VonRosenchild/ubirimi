<?php

use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\Util;

$noteId = $_POST['note_id'];
$content = $_POST['content'];

$date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

Note::updateById($noteId, $content, $date);

