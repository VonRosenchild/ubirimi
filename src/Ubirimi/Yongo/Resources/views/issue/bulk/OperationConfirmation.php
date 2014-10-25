<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Bulk Operation Step 4 of 4 > Confirmation'); ?>
    <div class="pageContent">

        <form action="/yongo/issue/bulk-change-confirmation" method="post" name="bulk_operation">
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/issue/bulk-choose?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>">Choose Issues</a></li>
                <li><a href="/yongo/issue/bulk-operation">Choose Operation</a></li>
                <li><a href="/yongo/issue/bulk-operation-details">Operation Details</a></li>
                <li class="active"><a>Confirmation</a></li>
            </ul>

            <?php if ($session->get('bulk_change_operation_type') == 'delete'): ?>

            <div>Please confirm that you wish to delete the following <?php echo count($session->get('bulk_change_issue_ids')) ?> issues.</div>
            <div>Deleting an issue removes it permanently from Yongo.</div>
            <div>Deleting an issue will remove all associated sub-tasks.</div>

            <?php if ($session->get('bulk_change_send_operation_email')): ?>
            <div>Email notifications will be sent for this update.</div>
            <?php endif ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="confirm">Confirm</button>
                        <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                    </td>
                </tr>
            </table>

            <?php
                $renderParameters = array('issues' => $issues, 'show_header' => true, 'render_checkbox' => false, 'sorting' => false);
                $renderColumns = array('code', 'summary', 'assignee', 'reporter', 'priority', 'status', 'resolution', 'date_created', 'date_updated');
                $issuesRendered = Util::renderIssueTables($renderParameters, $renderColumns, $clientSettings);
            ?>
            <?php endif ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="confirm">Confirm</button>
                        <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>