<form id="crontab_data">
    <table style="width: 700px" cellpadding="2px">
        <tr>
            <td colspan="5">
                <span>Recipients</span>
                <select id="recipient" class="select2Input">
                    <option value="u<?php echo $userId ?>">Personal Subscription</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>
                <div>Minute</div>
                <label for="minute_chooser_every" class="label label-primary">Every Minute</label>
                <input type="radio" name="minute_chooser"
                       id="minute_chooser_every" class="chooser" value="0"
                       checked="checked">
                <br />

                <label for="minute_chooser_choose" class="label label-primary">Choose</label>
                <input type="radio"
                       name="minute_chooser" id="minute_chooser_choose" class="chooser"
                       value="1">
                <br />

                <select name="minute" id="cron_minute"
                        multiple="multiple"
                        disabled="disabled" style="width: 100%"
                        size="8">
                    <?php for ($i = 0; $i <= 59; $i++): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor ?>
                </select>
            </td>
            <td>
                <div>Hour</div>
                <label for="hour_chooser_every" class="label label-primary">Every Hour</label>
                <input type="radio" name="hour_chooser" id="hour_chooser_every" class="chooser" value="0"
                       checked="checked">
                <br />

                <label for="hour_chooser_choose" class="label label-primary">Choose</label>
                <input type="radio" name="hour_chooser" id="hour_chooser_choose" class="chooser" value="1"><br>

                <select name="hour" id="cron_hour" multiple="multiple"
                        disabled="disabled" style="width: 100%"
                        size="8">
                    <option value="0">12 Midnight</option>
                    <option value="1">1 AM</option>
                    <option value="2">2 AM</option>
                    <option value="3">3 AM</option>
                    <option value="4">4 AM</option>
                    <option value="5">5 AM</option>
                    <option value="6">6 AM</option>
                    <option value="7">7 AM</option>
                    <option value="8">8 AM</option>
                    <option value="9">9 AM</option>
                    <option value="10">10 AM</option>
                    <option value="11">11 AM</option>
                    <option value="12">12 Noon</option>
                    <option value="13">1 PM</option>
                    <option value="14">2 PM</option>
                    <option value="15">3 PM</option>
                    <option value="16">4 PM</option>
                    <option value="17">5 PM</option>
                    <option value="18">6 PM</option>
                    <option value="19">7 PM</option>
                    <option value="20">8 PM</option>
                    <option value="21">9 PM</option>
                    <option value="22">10 PM</option>
                    <option value="23">11 PM</option>
                </select>
            </td>
            <td>
                <div>Day</div>
                <label for="day_chooser_every" class="label label-primary">Every Day</label>
                <input type="radio" name="day_chooser" id="day_chooser_every" class="chooser" value="0"
                       checked="checked"><br>

                <label for="day_chooser_choose" class="label label-primary">Choose</label>
                <input type="radio" name="day_chooser" id="day_chooser_choose" class="chooser" value="1"><br>

                <select name="day" id="cron_day" multiple="multiple" disabled="disabled" style="width: 100%"
                        size="8">
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor ?>
                </select>
            </td>
            <td>
                <div>Month</div>
                <label for="month_chooser_every" class="label label-primary">Every Month</label>
                <input type="radio" name="month_chooser" id="month_chooser_every" class="chooser" value="0"
                       checked="checked">
                <br />

                <label for="month_chooser_choose" class="label label-primary">Choose</label>
                <input type="radio" name="month_chooser" id="month_chooser_choose" class="chooser"
                       value="1">
                <br />

                <select name="month" id="cron_month" multiple="multiple" disabled="disabled" style="width: 100%"
                        size="8">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">Augest</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </td>
            <td>
                <div>Weekday</div>
                <label for="weekday_chooser_every" class="label label-primary">Every Weekday</label>
                <input type="radio" name="weekday_chooser" id="weekday_chooser_every" class="chooser" value="0"
                       checked="checked">
                <br />

                <label for="weekday_chooser_choose" class="label label-primary">Choose</label>
                <input type="radio" name="weekday_chooser" id="weekday_chooser_choose" class="chooser"
                       value="1">
                <br />

                <select name="weekday" id="cron_weekday" multiple="multiple" disabled="disabled" style="width: 100%"
                        size="8">
                    <option value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" id="email_when_empty" />
                Email this filter, even if there are no issues found
            </td>
        </tr>
    </table>
</form>