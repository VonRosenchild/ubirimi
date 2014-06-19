<?php
    use Ubirimi\QuickNotes\Repository\Note;
?>
<?php while ($allTags && $tagInList = $allTags->fetch_array(MYSQLI_ASSOC)): ?>
    <?php $noteInTag = Note::getFirstNoteByTagId($loggedInUserId, $tagInList['id']); ?>
    <div<?php if (isset($tagId) && $tagInList['id'] == $tagId) echo ' style="background-color: #EEEEEE;"' ?>>
        <span><a href="/quick-notes/tag/<?php echo $tagInList['id'] ?>/<?php echo $noteInTag['id'] ?>"><?php echo $tagInList['name'] ?> (<?php echo $tagInList['nr'] ?>)</a></span>
    </div>
<?php endwhile ?>