<?php
    $permissionSchemeId = $_GET['id'];
    $deletePossible = $_GET['delete_possible'];
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this permission scheme?
<?php else: ?>
    This permission scheme can no be deleted as it is associated with one or more projects.
<?php endif ?>