<?php
use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
use Ubirimi\Calendar\Repository\EventReminder;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Util;

/* check locking mechanism */
if (file_exists('process_event_reminders.lock')) {
    $fp = fopen('process_event_reminders.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_event_reminders task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$reminders = EventReminder::getRemindersToBeFired();

while ($reminders && $reminder = $reminders->fetch_array(MYSQLI_ASSOC)) {
    $currentDate = Util::getServerCurrentDateTime();
    $smtpSettings = SMTPServer::getByClientId($reminder['client_id']);

    if ($smtpSettings) {

        $reminderPeriod = $reminder['cal_event_reminder_period_id'];
        $reminderValue = $reminder['value'];

        $eventStartDate = $reminder['date_from'];

        $emailSubject = 'Reminder: ' . $reminder['name'] . ' @ ' . date('j M Y', strtotime($eventStartDate)) . ' (' . $reminder['calendar_name'] . ')';

        $emailBody = Util::getTemplate('_eventReminder.php', array(
            'event_name' => $reminder['name'],
            'when' => $eventStartDate,
            'calendar_name' => $reminder['calendar_name']));

        $dateTemporary = date_create(date('Y-m-d H:i:s', time()));

        switch ($reminderPeriod) {
            case CalendarEventReminderPeriod::PERIOD_MINUTE:
                date_add($dateTemporary, date_interval_create_from_date_string($reminderValue . ' minutes'));
                $eventStartDateReminder = date_format($dateTemporary, 'Y-m-d H:i:s');
                break;
            case CalendarEventReminderPeriod::PERIOD_HOUR:
                date_add($dateTemporary, date_interval_create_from_date_string($reminderValue . ' hours'));
                $eventStartDateReminder = date_format($dateTemporary, 'Y-m-d H:i:s');
                break;
            case CalendarEventReminderPeriod::PERIOD_DAY:
                date_add($dateTemporary, date_interval_create_from_date_string($reminderValue . ' days'));
                $eventStartDateReminder = date_format($dateTemporary, 'Y-m-d H:i:s');
                break;
            case CalendarEventReminderPeriod::PERIOD_WEEK:
                date_add($dateTemporary, date_interval_create_from_date_string($reminderValue . ' weeks'));
                $eventStartDateReminder = date_format($dateTemporary, 'Y-m-d H:i:s');
                break;
        }

        if ($eventStartDateReminder >= $eventStartDate) {

            // send the reminder
            $mailer = Email::getMailer($smtpSettings);
            $message = Swift_Message::newInstance($emailSubject)
                ->setFrom(array($smtpSettings['from_address']))
                ->setTo(array($reminder['email']))
                ->setBody($emailBody, 'text/html');

            @$mailer->send($message);

            // update the reminder as fired

            EventReminder::setAsFired($reminder['id']);
        }
    }
}

if (null !== $fp) {
    fclose($fp);
}