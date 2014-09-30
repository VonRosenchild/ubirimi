<tr>
    <td></td>
    <td align="left">
        <table id="add_event_repeat_daily_content" style="display: none;">
            <tr>
                <td>Repeat every</td>
                <td>
                    <select id="add_event_repeat_every_daily" class="select2InputSmall" style="width: 60px">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor ?>
                    </select>
                    days
                </td>
            </tr>
            <tr>
                <td>Starts on</td>
                <td><input type="text" id="add_event_repeat_start_date" class="inputText" disabled="disabled" value="<?php echo substr($defaultEventStartDate, 0, 10) ?>" style="width: 90px" /></td>
            </tr>
            <tr>
                <td valign="top">Ends</td>
                <td>
                    <input type="radio" name="repeat_data_daily" id="add_event_repeat_end_date_never_daily" value="1" checked="checked" />
                    <label for="add_event_repeat_end_date_never_daily">Never</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="radio" name="repeat_data_daily" id="add_event_repeat_end_date_after_occurrences_daily" />
                    <label for="add_event_repeat_end_date_after_occurrences_daily">
                        <span>After </span>
                    </label>
                    <input type="text"
                           class="inputText" style="width: 30px"
                           id="add_event_repeat_after_daily" />
                    <span>occurrences</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="radio" name ="repeat_data_daily" id="add_event_repeat_end_date_on_label" />
                    <label for="add_event_repeat_end_date_on_label">
                        <span>On </span>
                    </label>
                    <input type="text" value=""
                           class="inputText" id="add_event_repeat_end_date_on_daily"
                           style="width: 90px" />
                </td>
            </tr>
        </table>
        <table id="add_event_repeat_weekly_content" style="display: none;">
            <tr>
                <td>Repeat every</td>
                <td>
                    <select id="add_event_repeat_every_weekly" class="select2InputSmall" style="width: 60px">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor ?>
                    </select>
                    weeks
                </td>
            </tr>
            <tr>
                <td>Repeat on</td>
                <td>
                    <input <?php if (0 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_0" /> S
                    <input <?php if (1 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_1" /> M
                    <input <?php if (2 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_2" /> T
                    <input <?php if (3 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_3" /> W
                    <input <?php if (4 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_4" /> T
                    <input <?php if (5 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_5" /> F
                    <input <?php if (6 == $dayInWeek) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_6" /> S
                </td>
            </tr>
            <tr>
                <td>Starts on</td>
                <td><input type="text" id="add_event_repeat_start_date_weekly" class="inputText" disabled="disabled" value="<?php echo substr($defaultEventStartDate, 0, 10) ?>" style="width: 90px" /></td>
            </tr>
            <tr>
                <td valign="top">Ends</td>
                <td>
                    <input type="radio" name="repeat_data_weekly" id="add_event_repeat_end_date_never_weekly" value="1" checked="checked" />
                    <label for="add_event_repeat_end_date_never_weekly">Never</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="radio" name="repeat_data_weekly" id="add_event_repeat_end_date_after_occurrences_weekly" />
                    <label for="add_event_repeat_end_date_after_occurrences_weekly">
                        <span>After </span>
                    </label>
                    <input type="text" class="inputText" style="width: 30px" id="add_event_repeat_after_weekly" />
                    <span>occurrences</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="radio" name="repeat_data_weekly" id="add_event_repeat_end_date_on_weekly_label" />
                    <label for="add_event_repeat_end_date_on_weekly_label">
                        <span>On </span>
                    </label>
                    <input type="text" value="" class="inputText" id="add_event_repeat_end_date_on_weekly" style="width: 90px" />
                </td>
            </tr>
        </table>
    </td>
</tr>