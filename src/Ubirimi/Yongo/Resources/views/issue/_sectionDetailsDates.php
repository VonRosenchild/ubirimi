<?php
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

?>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle headerPageText">Dates</span></td>
    </tr>
</table>
<table width="100%" id="contentDates">
    <tr>
        <td width="120">
            <div class="textLabel">Due:</div>
        </td>
        <td>
            <?php if ($issue[Field::FIELD_DUE_DATE_CODE]) echo Util::getFormattedDate($issue[Field::FIELD_DUE_DATE_CODE], $clientSettings['timezone']) ?>
        </td>
    </tr>

    <tr>
        <td width="120">
            <div class="textLabel">Created:</div>
        </td>
        <td><?php if ($issue['date_created']) echo Util::getFormattedDate($issue['date_created'], $clientSettings['timezone']) ?></td>
    </tr>
    <tr>
        <td>
            <div class="textLabel">Updated:</div>
        </td>
        <td><?php if ($issue['date_updated'])
                echo Util::getFormattedDate($issue['date_updated'], $clientSettings['timezone']) ?></td>
    </tr>
    <tr>
        <td>
            <div class="textLabel">Resolved:</div>
        </td>
        <td><?php if ($issue['date_resolved']) echo Util::getFormattedDate($issue['date_resolved'], $clientSettings['timezone']) ?></td>
    </tr>
</table>