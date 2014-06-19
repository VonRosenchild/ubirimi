<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this screen?
<?php else: ?>
    This screen can not be deleted. It is associated with one or more screen schemes, or one or more workflow transitions.
<?php endif ?>