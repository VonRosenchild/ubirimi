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
        <?php if ($slaData['goal']): ?>
            <tr>
                <td width="200" valign="top">
                    <div class="textLabel"><?php echo $slaData['name'] ?></div>
                    <div><?php echo 'within ' . $slaData['goal'] ?> minutes</div>
                </td>
                <td valign="top">
                    <span class="<?php if (($slaData['goal'] - $slaData['offset']) < 0) echo 'slaNegative'; else echo 'slaPositive' ?>">
                        <?php echo SLA::formatOffset($slaData['goal'] - $slaData['offset']) ?>
                    </span>
                    &nbsp;
                    <img src="/img/clock.png" height="16px" />
                </td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>