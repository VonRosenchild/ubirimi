<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this issue type scheme?
<?php else: ?>
    This issue type scheme can not be deleted. It is associated with one or more projects.
<?php endif ?>