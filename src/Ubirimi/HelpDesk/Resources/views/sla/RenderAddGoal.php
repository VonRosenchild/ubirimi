<tr>
    <td>
        <textarea class="inputTextAreaLarge goal_autocomplete"
                  name="goal_definition_<?php echo $time ?>"
                  id="goal_definition_<?php echo $time ?>"></textarea>
    </td>
    <td valign="top">
        <input size="5"
               type="text"
               class="inputText"
               value=""
               style="width: 110px"
               name="goal_value_<?php echo $time ?>" /> minutes
    </td>
    <td>
        <select name="goal_calendar_<?php echo $time ?>" class="select2InputSmall">
            <?php while ($calendar = $slaCalendars->fetch_array(MYSQLI_ASSOC)): ?>
                <option value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
            <?php endwhile ?>
        </select>
    </td>
    <td>
        <button type="button"
                id="delete_goal_<?php echo $time ?>"
                class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</button>
    </td>
</tr>