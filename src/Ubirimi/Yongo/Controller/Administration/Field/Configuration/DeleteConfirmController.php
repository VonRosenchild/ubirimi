<?php
    $deletePossible = $_GET['delete_possible']
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this field configuration?
<?php else: ?>
    This field configuration can not be deleted. It is associated with a field configuration scheme.
<?php endif ?>