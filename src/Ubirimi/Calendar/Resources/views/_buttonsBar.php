<table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
    <tr>
        <td>
            <a href="/calendar/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Calendar</a>
        </td>
        <td>
            <a class="btn ubirimi-btn" href="/calendar/view/<?php echo implode('|', $calendarIds) ?>/<?php echo $previousMonth ?>/<?php echo $previousYear ?>"><</a>
        </td>
        <td>
            <a href="/calendar/view/<?php echo implode('|', $calendarIds) ?>/<?php echo $nextMonth ?>/<?php echo $nextYear ?>" class="btn ubirimi-btn">></a>
        </td>
        <td>
            <div style="font: 17px Trebuchet MS, sans-serif;"><?php echo $currentMonthName . ' ' . $year ?></div>
        </td>
    </tr>
</table>