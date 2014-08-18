<?php
    use Ubirimi\Repository\HelpDesk\SLA;
?>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle headerPageText">SLAs</span></td>
    </tr>
</table>
<table width="100%" id="contentDates">
    <?php
        foreach ($slasPrintData as $slaId => $slaData): ?>
        <?php if ($slaData['goalValue']): ?>
            <tr>
                <td width="200" valign="top">
                    <div class="textLabel"><?php echo $slaData['name'] ?></div>
                    <div><?php echo 'within ' . $slaData['goalValue'] ?> minutes</div>
                </td>
                <td valign="top">
                    <span class="<?php if (($slaData['goalValue'] - $slaData['intervalMinutes'] - $slaData['valueBetweenCycles']) < 0) echo 'slaNegative'; else echo 'slaPositive' ?>">
                        <?php echo SLA::formatOffset($slaData['goalValue'] - $slaData['intervalMinutes'] - $slaData['valueBetweenCycles']) ?>
                    </span>
                    &nbsp;
                    <?php if ($slaData['endDate']): ?>
                        <img src="/img/accept.png" />
                    <?php else: ?>
                        <img src="/img/clock.png" height="16px" />
                    <?php endif ?>
                </td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>