<?php
    $issueSecuritySchemeId = $_GET['id'];
    $deletePossible = $_GET['delete_possible'];
?>
<?php if ($deletePossible): ?>
    Are you sure you want to delete this issue security scheme?
<?php else: ?>
    This issue security scheme can not be deleted because it is assiciated with one or more projects.
<?php endif ?>