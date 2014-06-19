<?php

    use Ubirimi\Calendar\Repository\Calendar;

    $calendarId = $_GET['id'];
    $calendar = Calendar::getById($calendarId);
    $defaultCalendar = false;
    if (1 == $calendar['default_flag']) {
        $defaultCalendar = true;
    }
?>
<div>Are you sure you want to delete this calendar?</div>
<?php if ($defaultCalendar): ?>
    <div>This is your primary calendar. This operation can not be undone.</div>
<?php endif ?>