<?php
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

?>

    <div class="issueSummaryTitle"><?php echo $issue['summary'] ?></div>
    <table width="100%">
        <tr>
            <td colspan="6">Details</td>
            <td colspan="2">Date history</td>
        </tr>
        <tr>
            <td>Priority</td>
            <td><?php echo $issue['priority_name'] ?></td>
            <td>Assigned to</td>
            <td><?php if ($issue[Field::FIELD_ASSIGNEE_CODE])
                    echo $issue['ua_first_name'] . ' ' . $issue['ua_last_name']; else echo 'No one' ?></td>
            <td>Components</td>
            <td>
                <?php if ($components): ?>
                    <?php while ($component = $components->fetch_array(MYSQLI_ASSOC)): ?>
                        <span><?php echo $component['name'] ?></span>
                    <?php endwhile ?>
                <?php else: ?>
                    <span>None</span>
                <?php endif ?>
            </td>
            <td>Created on</td>
            <td><?php echo Util::getFormattedDate($issue['date_created'], $clientSettings['timezone']) ?></td>
        </tr>
        <tr>
            <td>Type</td>
            <td><?php echo $issue['type']; ?></td>
            <td>Reported by</td>
            <td><?php echo $issue['ur_first_name'] . ' ' . $issue['ur_last_name'] ?></td>
            <td>Affected version</td>
            <td>
                <?php if ($versionsAffected): ?>
                    <?php while ($version = $versionsAffected->fetch_array(MYSQLI_ASSOC)): ?>
                        <span><?php echo $version['name'] ?></span>
                    <?php endwhile ?>
                <?php else: ?>
                    <span>None</span>
                <?php endif ?>
            </td>
            <td>Updated on</td>
            <td><?php echo Util::getFormattedDate($issue['date_updated'], $clientSettings['timezone']) ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td colspan="3"><?php echo $issue['status_name'] ?></td>
            <td>Fix version/s</td>
            <td>
                <?php if ($versionsTargeted): ?>
                    <?php while ($version = $versionsTargeted->fetch_array(MYSQLI_ASSOC)): ?>
                        <span><?php echo $version['name'] ?></span>
                    <?php endwhile ?>
                <?php else: ?>
                    <span>None</span>
                <?php endif ?>
            </td>
            <td>Resolved on</td>
            <td><?php echo Util::getFormattedDate($issue['date_resolved'], $clientSettings['timezone']) ?></td>
        </tr>
        <tr>
            <td colspan="8">Description</td>
        </tr>
        <tr>
            <td colspan="8"><?php echo str_replace("\n", '<br />', $issue['description']) ?></td>
        </tr>
    </table>

<?php if ($countAttachments > 0): ?>
    <table width="100%" id="content_list" cellspacing="0" cellpadding="3">
        <tr>
            <td colspan="2">Attachments</td>
        </tr>
        <?php while ($attachment = $attachments->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td><?php echo $attachment['name'] ?></td>
                <td>
                    added by <?php echo $attachment['first_name'] . ' ' . $attachment['last_name'] ?> on <?php echo $attachment['date_created'] ?>
                </td>
            </tr>
            <?php if (Util::isImage(Util::getExtension($attachment['name']))): ?>
                <tr>
                    <td colspan="2">
                        <img src="/yongo/attachments/<?php echo $issueId ?>/<?php echo $attachment['name'] ?>"/>
                    </td>
                </tr>
            <?php endif ?>
        <?php endwhile ?>
    </table>
<?php endif ?>