<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Bulk Operation Step 2 of 4 > Choose Operation'); ?>
    <div class="pageContent">
        <form action="/yongo/issue/bulk-operation" method="post" name="bulk_operation">
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/issue/bulk-choose?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>">Choose Issues</a></li>
                <li class="active"><a>Choose Operation</a></li>
                <li class="disabled"><a>Operation Details</a></li>
                <li class="disabled"><a>Confirmation</a></li>
            </ul>

            <form name="choose_operation" method="post" action="/yongo/issue/bulk-operation">
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td>
                            <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="next_step_3">Next Step</button>
                            <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                        </td>
                    </tr>
                </table>
                <div>Choose the operation you wish to perform on the selected issues.</div>
                <br />
                <div>
                    <table width="100%" cellspacing="0">
                        <tr>
                            <td width="30">
                                <?php if ($deletePermissionInAllProjects): ?>
                                    <input checked="checked" id="operation_delete" type="radio" value="Delete Issues" name="operation_delete" />
                                <?php else: ?>
                                    <div>N/A</div>
                                <?php endif ?>
                            </td>
                            <td width="150px">
                                <div>Delete Issues</div>
                            </td>
                            <td>
                                <?php if ($deletePermissionInAllProjects): ?>
                                    <label for="operation_delete">Permanently delete issues from Yongo</label>
                                <?php else: ?>
                                    <div>NOTE: You do not have permission to delete the selected issues.</div>
                                <?php endif ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php if (!$operationSelected): ?>
                    <div class="errorWithBackground">You must select an operation</div>
                <?php endif ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td>
                            <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="next_step_3">Next Step</button>
                            <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        </form>
    </div>
</body>