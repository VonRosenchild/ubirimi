<?php

use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\Util;

$noteId = $_POST['note_id'];
$content = $_POST['content'];

$date = Util::getServerCurrentDateTime();

Note::updateById($noteId, $content, $date);

