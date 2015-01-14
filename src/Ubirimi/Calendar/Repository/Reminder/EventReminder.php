<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Calendar\Repository\Reminder;

use Ubirimi\Container\UbirimiContainer;

class EventReminder
{
    public function getRemindersToBeFired() {
        $query = "SELECT cal_event.date_from, cal_event.name, " .
                     "cal_event_reminder.cal_event_reminder_period_id, cal_event_reminder.value, cal_event_reminder.id,  " .
                     "general_user.client_id, general_user.email, " .
                     "cal_calendar.name as calendar_name, " .
                     "client.timezone " .
                 "from cal_event_reminder " .
                 "left join cal_event on cal_event.id = cal_event_reminder.cal_event_id " .
                 "left join general_user on general_user.id = cal_event.user_created_id " .
                 "left join client on client.id = general_user.client_id " .
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
