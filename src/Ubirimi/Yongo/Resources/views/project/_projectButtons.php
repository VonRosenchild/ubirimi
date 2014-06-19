<?php if (isset($hasAdministerProject) && $hasAdministerProject): ?>
    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td>
                <a id="btnNew" href="/yongo/administration/project/<?php echo $projectId ?>" class="btn ubirimi-btn">Administer Project</a>
            </td>
        </tr>
    </table>
<?php else: ?>
    <div class="separationVertical"></div>
<?php endif ?>