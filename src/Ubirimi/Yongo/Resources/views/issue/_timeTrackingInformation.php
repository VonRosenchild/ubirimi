<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\WorkLog;

// determine the percentages
// the biggest time is 100%
$originalEstimate = Util::transformLogTimeToMinutes($issue['original_estimate'], $hoursPerDay, $daysPerWeek);
$remainingEstimate = Util::transformLogTimeToMinutes($issue['remaining_estimate'], $hoursPerDay, $daysPerWeek);
$worklogs = UbirimiContainer::get()['repository']->get(WorkLog::class)->getByIssueId($issue['id']);
$minutesLogged = 0;
while ($worklogs && $worklog = $worklogs->fetch_array(MYSQLI_ASSOC)) {
    $minutesLogged += Util::transformLogTimeToMinutes($worklog['time_spent'], $hoursPerDay, $daysPerWeek);
}

$percOriginalEstimate = 0;
$percRemainingEstimate = 0;
$percMinuteskLogged = 0;

$max = max(array($originalEstimate, $remainingEstimate, $minutesLogged));

// find the percentage for each value
$percOriginalEstimate = $originalEstimate * 100 / $max;
$percRemainingEstimate = $remainingEstimate * 100 / $max;
$percMinuteskLogged = $minutesLogged * 100 / $max;

?>

<table width="100%" id="contentTimeTracking">
    <tr>
        <td width="80"><div class="textLabel">Estimated:</div></td>
        <td width="110px" align="right"><?php echo Util::transformTimeToString(Util::transformLogTimeToMinutes($issue['original_estimate'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek, 'short'); ?></td>
        <td valign="middle">
            <div style="background-color: #d3d3d3; width: 100%; height: 14px; margin-top: 2px">
                <div style="float:left; background-color: #56A5EC; height: 14px; width: <?php echo $percOriginalEstimate ?>%"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td><div class="textLabel">Remaining:</div></td>
        <td align="right"><?php if ($issue['remaining_estimate'] == "0") echo "0"; else echo Util::transformTimeToString(Util::transformLogTimeToMinutes($issue['remaining_estimate'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek, 'short'); ?></td>
        <td valign="middle">
            <div style="background-color: #d3d3d3; width: 100%; height: 14px; margin-top: 2px">
                <div style=" float:right; background-color: #ec9a1f; height: 14px; width: <?php echo $percRemainingEstimate ?>%"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td><div class="textLabel">Logged:</div></td>
        <td align="right">
            <?php
                if ($worklogs)
                    echo Util::transformTimeToString($minutesLogged, $hoursPerDay, $daysPerWeek, 'short');
                else
                    echo 'Not Specified'
            ?>
        </td>
        <td valign="middle">
            <div style="background-color: #d3d3d3; width: 100%; height: 14px; margin-top: 2px">
                <div style="float:left; background-color: #77ce5c; height: 14px; width: <?php echo $percMinuteskLogged ?>%"></div>
            </div>
        </td>
    </tr>
</table>