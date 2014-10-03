<table cellspacing="0" cellpadding="2" border="0">
    <tr>
        <td width="20px" align="left" style="vertical-align: middle">
            <input type="checkbox" <?php if (isset($slaConditions) && in_array($prefixElementName . '_issue_created', $slaConditions)) echo 'checked="checked"' ?>
                   value="1"
                   name="<?php echo $prefixElementName ?>_issue_created" />
        </td>
        <td><span>Issue Created</span></td>
    </tr>
    <tr>
        <td width="20px" style="vertical-align: middle">
            <input type="checkbox" <?php if (isset($slaConditions) && in_array($prefixElementName . '_assignee_changed', $slaConditions)) echo 'checked="checked"' ?>
                   value="1"
                   name="<?php echo $prefixElementName ?>_assignee_changed" />
        </td>
        <td><span>Assignee changed</span></td>
    </tr>
    <tr>
        <td width="20px" style="vertical-align: middle">
            <input type="checkbox" <?php if (isset($slaConditions) && in_array($prefixElementName . '_resolution_set', $slaConditions)) echo 'checked="checked"' ?>
                   value="1"
                   name="<?php echo $prefixElementName ?>_resolution_set" />
        </td>
        <td><span>Resolution set</span></td>
    </tr>
    <?php while ($availableStatuses && $availableStatus = $availableStatuses->fetch_array(MYSQLI_ASSOC)): ?>
        <tr>
            <td width="20px" style="vertical-align: middle">
                <input type="checkbox"
                       value="1"
                        <?php if (isset($slaConditions) && in_array($prefixElementName . '_status_set_' . $availableStatus['id'], $slaConditions)) echo 'checked="checked"' ?>
                       name="<?php echo $prefixElementName ?>_status_set_<?php echo $availableStatus['id'] ?>" />
            </td>
            <td><span>Entered Status: <?php echo $availableStatus['name'] ?></span></td>
        </tr>
    <?php endwhile ?>
    <tr>
        <td width="20px" style="vertical-align: middle">
            <input type="checkbox" <?php if (isset($slaConditions) && in_array($prefixElementName . '_comment_by_assignee', $slaConditions)) echo 'checked="checked"' ?>
                   value="1"
                   name="<?php echo $prefixElementName ?>_comment_by_assignee" />
        </td>
        <td><span>Comment: By Assignee</span></td>
    </tr>
</table>