<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb('Attachments') ?>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/attachment-configuration">Attachments</a></li>
            <li><a href="/yongo/administration/events">Events</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/edit-attachment-configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Settings</a></td>
            </tr>
        </table>
        <table class="table table-hover table-condensed">
            <tbody>
                <tr>
                    <td width="30%">Allow Attachments</td>
                    <td><?php if ($settings['allow_attachments_flag']) echo 'Yes'; else echo 'No' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>