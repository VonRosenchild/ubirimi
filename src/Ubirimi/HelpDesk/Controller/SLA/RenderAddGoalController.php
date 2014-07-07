<?php
    use Ubirimi\Repository\HelpDesk\SLACalendar;
    $time = time();

    $projectId = $_POST['project_id'];
    $slaCalendars = SLACalendar::getByProjectId($projectId);
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
    </td>
    <td>
        <select name="goal_calendar" class="inputTextCombo">
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