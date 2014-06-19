<?php
    $data = $_POST['data'];
    $dataValues = json_decode($data, true);
    $textSelected = 'checked="checked"';

    echo '<table id="parent_issue_possible_transitions">';
        echo '<tr>';
            echo '<td>';
                echo '<span>All sub-tasks for parent issue are now Done.</span>';
                echo '<br />';
                echo '<span>To update this parent issue to match, select a transition:</span>';
            echo '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>';
                foreach ($dataValues as $transition) {
                    $Id = 'parent_possible_transition_' . $transition['screen'] . '_' . $transition['step_id_from'] . '_' . $transition['step_id_to'] . '_' . $transition['workflow_id'] . '_' . $transition['project_id'];
                    echo '<input ' . $textSelected . ' id="' . $Id .'" type="radio" name="transitions_for_parent_issue" value="' . $transition['transition_name'] . '" />';
                    echo '<label for="' . $Id . '">' . $transition['transition_name'] . '</label>';
                    echo '<br />';
                    $textSelected = '';
                }
            echo '</td>';
        echo '</tr>';
    echo '</table>';