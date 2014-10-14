<?php if ($workflowUsed): ?>
    <?php require_once __DIR__ . '/../../../Resources/views/issue/_dialogCreate.php'; ?>
<?php else: ?>
<tr>
    <td colspan="2">
        <div class="infoBox">There is no workflow set for this issue type.</div>
    </td>
</tr>
<?php endif ?>