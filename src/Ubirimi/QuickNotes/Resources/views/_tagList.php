<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\QuickNotes\Repository\Note;
?>
<?php while ($allTags && $tagInList = $allTags->fetch_array(MYSQLI_ASSOC)): ?>
    <?php $noteInTag = UbirimiContainer::get()['repository']->get(Note::class)->getFirstNoteByTagId($loggedInUserId, $tagInList['id']); ?>
    <div<?php if (isset($tagId) && $tagInList['id'] == $tagId) echo ' style="background-color: #EEEEEE;"' ?>>
        <span><a href="/quick-notes/tag/<?php echo $viewType ?>/<?php echo $tagInList['id'] ?>/<?php if (isset($noteInTag['id'])) echo $noteInTag['id']; else echo '-1'; ?>"><?php echo $tagInList['name'] ?> (<?php echo $tagInList['nr'] ?>)</a></span>
    </div>
<?php endwhile ?>