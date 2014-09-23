<tr>
    <td></td>
    <td>
        <table id="add_event_repeat_daily_content" style="display: none;">
            <tr>
                <td>Repeat every</td>
                <td>
                    <select id="add_event_repeat_every_daily" name="add_event_repeat_every_daily" class="inputTextCombo" style="width: 50px">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option <?php if ($repeatDaily && $defaultEventRepeatEvery == $i) echo 'selected="selected"' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor ?>
                    </select>
                    days
                </td>
            </tr>
            <tr>
                <td>Starts on</td>
                <td>
                    <input type="text"
                           id="add_event_repeat_start_date"
                           name="add_event_repeat_start_date"
                           class="inputText"
                           readonly="readonly"
                           value="<?php echo substr($defaultEventStartDate, 0, 10) ?>"
                           style="width: 90px" />
                </td>
            </tr>
            <tr>
                <td valign="top">Ends</td>
                <td>
                    <input type="radio"
                           name="repeat_data_daily"
                           id="add_event_repeat_end_date_never_daily"
                           value="never"
                           checked="checked" />
                    <label for="add_event_repeat_end_date_never_daily">Never</label>
                    <br />
                    <input type="radio"
                           value="after"
                           name="repeat_data_daily"
                           <?php if ($repeatDaily && $defaultEventEndAfterOccurrences) echo 'checked="checked"'; ?>
                           id="add_event_repeat_end_date_after_occurrences_daily" />
                    <label for="add_event_repeat_end_date_after_occurrences_daily">
                        <span>After </span>
                    </label>
                    <input value="<?php if ($repeatDaily && $defaultEventEndAfterOccurrences) echo $defaultEventEndAfterOccurrences ?>"
                           type="text"
                           class="inputText"
                           style="width: 30px"
                           name="add_event_repeat_after_daily"
                           id="add_event_repeat_after_daily" />
                    <span>occurrences</span>
                    <br />
                    <input type="radio"
                           value="on"
                           name="repeat_data_daily"
                           <?php if ($repeatDaily && $defaultEventEndDate) echo 'checked="checked"' ?>
                           id="add_event_repeat_end_date_on_label" />
                    <label for="add_event_repeat_end_date_on_label">
                        <span>On </span>
                    </label>
                    <input value="<?php if ($repeatDaily && $defaultEventEndDate) echo $defaultEventEndDate ?>"
                           type="text"
                           class="inputText"
                           id="add_event_repeat_end_date_on_daily"
                           name="add_event_repeat_end_date_on_daily"
                           style="width: 90px" />
                </td>
            </tr>
        </table>

        <table id="add_event_repeat_weekly_content" style="display: none;">
            <tr>
                <td>Repeat every</td>
                <td>
                    <select id="add_event_repeat_every_weekly" name="add_event_repeat_every_weekly" class="select2InputSmall" style="width: 50px">
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
                    <input <?php if ($eventRepeatData['on_day_0']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_0" name="week_on_0" /> S
                    <input <?php if ($eventRepeatData['on_day_1']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_1" name="week_on_1"/> M
                    <input <?php if ($eventRepeatData['on_day_2']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_2" name="week_on_2"/> T
                    <input <?php if ($eventRepeatData['on_day_3']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_3" name="week_on_3"/> W
                    <input <?php if ($eventRepeatData['on_day_4']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_4" name="week_on_4"/> T
                    <input <?php if ($eventRepeatData['on_day_5']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_5" name="week_on_5"/> F
                    <input <?php if ($eventRepeatData['on_day_6']) echo 'checked="checked"' ?> type="checkbox" value="1" id="week_on_6" name="week_on_6"/> S
                </td>
            </tr>
            <tr>
                <td>Starts on</td>
                <td><input type="text"
                           id="add_event_repeat_start_date_weekly"
                           name="add_event_repeat_start_date_weekly"
                           class="inputText"
                           readonly="readonly"
                           value="<?php echo substr($defaultEventStartDate, 0, 10) ?>"
                           style="width: 90px" />
                </td>
            </tr>
            <tr>
                <td valign="top">Ends</td>
                <td>
                    <input type="radio"
                           name="repeat_data_weekly"
                           id="add_event_repeat_end_date_never_weekly"
                           value="1"
                           checked="checked" />
                    <label for="add_event_repeat_end_date_never_weekly">Never</label>
                    <br />
                    <input type="radio"
                           name="repeat_data_weekly"
                           <?php if ($repeatWeekly && $defaultEventEndAfterOccurrences) echo 'checked="checked"'; ?>
                           id="add_event_repeat_end_date_after_occurrences_weekly" />
                    <label for="add_event_repeat_end_date_after_occurrences_weekly">
                        <span>After </span>
                    </label>
                    <input type="text"
                           value="<?php if ($repeatWeekly && $defaultEventEndAfterOccurrences) echo $defaultEventEndAfterOccurrences ?>
                           class="inputText"
                           style="width: 30px"
                           id="add_event_repeat_after_weekly"
                           name="add_event_repeat_after_weekly" />
                    <span>occurrences</span>
                    <br />
                    <input type="radio"
                           name="repeat_data_weekly"
                           <?php if ($repeatWeekly && $defaultEventEndDate) echo 'checked="checked"' ?>
                           id="add_event_repeat_end_date_on_weekly_label" />
                    <label for="add_event_repeat_end_date_on_weekly_label">
                        <span>On </span>
                    </label>
                    <input type="text"
                           value="<?php if ($repeatWeekly && $defaultEventEndDate) echo $defaultEventEndDate ?>"
                           class="inputText"
                           id="add_event_repeat_end_date_on_weekly"
                           name="add_event_repeat_end_date_on_weekly"
                           style="width: 90px" />
                </td>
            </tr>
        </table>
    </td>
</tr>