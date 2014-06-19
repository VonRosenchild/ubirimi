<tr>
    <td></td>
    <td>
        <table id="add_event_repeat_daily_content" style="display: none;">
            <tr>
                <td>Repeat every</td>
                <td>
                    <select id="add_event_repeat_every" class="inputTextCombo" style="width: 50px">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Starts on</td>
                <td><input type="text" id="add_event_repeat_start_date" class="inputText" disabled="disabled" value="<?php echo substr($defaultDate, 0, 10) ?>" style="width: 90px" /></td>
            </tr>
            <tr>
                <td valign="top">Ends</td>
                <td>
                    <input type="radio" name="add_event_repeat_end_date" id="add_event_repeat_end_date_never" value="1" checked="checked" />
                    <label for="add_event_repeat_end_date">Never</label>
                    <br />
                    <input type="radio" name="add_event_repeat_end_date" id="add_event_repeat_end_date_after_occurrences" />
                    <label for="add_event_repeat_end_date_after_occurrences">
                        <span>After </span>
                    </label>
                    <input type="text" class="inputText" style="width: 30px" id="add_event_repeat_after" />
                    <span>occurrences</span>
                    <br />
                    <input type="radio" name="add_event_repeat_end_date" id="add_event_repeat_end_date_on" />
                    <label for="add_event_repeat_end_date_on">
                        <span>On </span>
                    </label>
                    <input type="text" value="" class="inputText" id="add_event_repeat_end_date_on" style="width: 90px" />
                </td>
            </tr>
        </table>
    </td>
</tr>