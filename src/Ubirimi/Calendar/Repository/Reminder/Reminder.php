<?php

namespace Ubirimi\Calendar\Repository\Reminder;

use Ubirimi\Container\UbirimiContainer;

class Reminder
{
    public function getRemindersToBeFired() {
        $query = "SELECT cal_event.date_from, cal_event.name, " .
                     "cal_event_reminder.cal_event_reminder_period_id, cal_event_reminder.value, cal_event_reminder.id,  " .
                     "user.client_id, user.email, " .
                     "cal_calendar.name as calendar_name, " .
                     "client.timezone " .
                 "from cal_event_reminder " .
                 "left join cal_event on cal_event.id = cal_event_reminder.cal_event_id " .
                 "left join user on user.id = cal_event.user_created_id " .
                 "left join client on client.id = user.client_id " .
                 "left join cal_calendar on cal_calendar.id = cal_event.cal_calendar_id " .
                 "where fired_flag is null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function setAsFired($reminderId) {
        $query = "update cal_event_reminder set fired_flag = 1 where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $reminderId);
        $stmt->execute();
    }

    public function deleteById($reminderId) {
        $query = "delete from cal_event_reminder where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $reminderId);
        $stmt->execute();
    }
}
