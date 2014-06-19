<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this field configuration scheme?
<?php else: ?>
    This field configuration scheme can not be deleted. It is associated with a project.
<?php endif ?>