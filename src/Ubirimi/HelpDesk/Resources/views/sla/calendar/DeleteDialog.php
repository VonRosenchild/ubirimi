<?php if ($slas): ?>
    <div>This calendar can not be deleted as it is in use in the following SLAs:</div>
    <?php echo implode(", ", $slas) ?>
<?php else: ?>
    Are you sure you want to delete this calendar?
<?php endif ?>