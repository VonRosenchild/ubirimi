<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Bulk Operation Step 3 of 4 > Operation Details'); ?>
    <div class="pageContent">
        <form action="/yongo/issue/bulk-operation-details" method="post" name="bulk_operation">
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/issue/bulk-choose?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>">Choose Issues</a></li>
                <li><a href="/yongo/issue/bulk-operation">Choose Operation</a></li>
                <li class="active"><a>Operation Details</a></li>
                <li class="disabled"><a>Confirmation</a></li>
            </ul>

            <input type="checkbox" value="1" id="send_email_operation" name="send_email" checked="checked" />
            <label for="send_email_operation">Send mail for this update</label>
            <div class="description">By selecting this option, an update notification will be sent for each issue affected by this bulk operation</div>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="next_step_4">Next Step</button>
                        <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>