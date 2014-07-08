<table id="parent_issue_possible_transitions">
    <tr>
        <td>
            <span>All sub-tasks for parent issue are now Done.</span>
            <br />
            <span>To update this parent issue to match, select a transition:</span>
        </td>
    </tr>
    <tr>
        <td>
            <?php foreach ($dataValues as $transition): ?>
            <?php $id = 'parent_possible_transition_'
                    . $transition['screen']
                    . '_' . $transition['step_id_from']
                    . '_' . $transition['step_id_to']
                    . '_' . $transition['workflow_id']
                    . '_' . $transition['project_id'];
            ?>
            <input <?php echo $textSelected ?> id="<?php echo $id ?>" type="radio" name="transitions_for_parent_issue" value="<?php echo $transition['transition_name'] ?>" />
            <label for="<?php echo $id ?>"><?php echo $transition['transition_name'] ?></label>
            <br />
            <?php $textSelected = ''; ?>
            <?php endforeach ?>
        </td>
    </tr>
</table>