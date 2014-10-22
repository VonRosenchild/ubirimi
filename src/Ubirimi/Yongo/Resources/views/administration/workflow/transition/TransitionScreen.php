<div>Transition name: <input class="inputText" type="text" value="<?php echo $transitionName ?>" id="transition_name_modal" /></div>
<div style="height: 4px"></div>
<?php if ($initialStep['id'] != $stepIdFrom): ?>
    <div>
        <span>Set screen for transition:</span>
        <select name="screen_modal" id="screens_modal" class="select2InputSmall">
            <option <?php if (!$workflowData['screen_id']) echo 'selected="selected"' ?> value="-1">No screen</option>
            <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                <option <?php if ($screen['id'] == $workflowData['screen_id']) echo 'selected="selected"' ?> value="<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></option>
            <?php endwhile ?>
        </select>
    <div>
<?php endif ?>
<input type="hidden" value="<?php echo $stepIdFrom ?>" id="id_from_modal" />
<input type="hidden" value="<?php echo $stepIdTo ?>" id="id_to_modal" />
<input type="hidden" value="<?php echo $workflowId ?>" id="project_workflow_id_modal" />