<?php
    $notificationSchemeId = $_GET['id'];
    $deletePossible = $_GET['delete_possible'];
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this notification scheme?
<?php else: ?>
    This notification scheme can no be deleted as it is associated with one or more projects.
<?php endif ?>