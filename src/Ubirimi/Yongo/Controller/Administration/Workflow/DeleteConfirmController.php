<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this workflow?
<?php else: ?>
    This workflow can not be deleted. It is associated with one or more workflow schemes.
<?php endif ?>