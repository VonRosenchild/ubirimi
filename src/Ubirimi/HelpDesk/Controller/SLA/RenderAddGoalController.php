<?php
    $time = time();
?>
<tr>
    <td>
        <textarea class="inputTextAreaLarge goal_autocomplete"
                  name="goal_definition_<?php echo $time ?>"
                  id="goal_definition_<?php echo $time ?>"></textarea>
    </td>
    <td valign="top">
        <input size="5"
               type="text"
               value=""
               name="goal_value_<?php echo $time ?>" /> minutes
        <button type="button"
                id="delete_goal_<?php echo $time ?>"
                class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</button>
    </td>
</tr>