<?php if ($issues): ?>
    <div>There are issues currently set with this Issue Security Level. Confirm what new level should be set for these issues.</div>
    <div>Issues with this level of security: <?php echo $issues->num_rows ?></div>
    <div>
        <span>Swap security of issues to security level: </span>
        <select class="inputTextCombo" name="new_level" id="new_security_level">
            <option value="-1">None</option>
            <?php while ($allLevels && $level = $allLevels->fetch_array(MYSQLI_ASSOC)): ?>
                <?php if ($level['id'] != $issueSecuritySchemeLevelId): ?>
                    <option value="<?php echo $level['id'] ?>"><?php echo $level['name'] ?></option>
                <?php endif ?>
            <?php endwhile ?>
        </select>
    </div>
<?php else: ?>
    Are you sure you want to delete this issue security level?
<?php endif ?>