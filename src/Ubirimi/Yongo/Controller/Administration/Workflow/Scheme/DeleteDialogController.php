<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this workflow scheme?
<?php else: ?>
    This workflow scheme can not be deleted. It is associated with one or more projects.
<?php endif ?>