<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this screen scheme?
<?php else: ?>
    This screen scheme can not be deleted. It is associated with one or more issue type screen schemes.
<?php endif ?>