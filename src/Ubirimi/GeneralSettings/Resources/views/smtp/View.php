<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Mail > SMTP Settings') ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <?php if ($smtpSettings): ?>
                        <td><a href="#" class="btn ubirimi-btn disabled"><i class="icon-plus"></i> Configure New SMTP Server</a></td>
                        <td>
                            <?php if ($smtpSettings['default_ubirimi_server_flag']): ?>
                                <a class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a>
                            <?php else: ?>
                                <a href="/general-settings/smtp-settings/edit/<?php echo $smtpSettings['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                            <?php endif ?>
                        </td>
                        <td><a id="btnDeleteSMTPServer" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
                    <?php else: ?>
                        <td><a href="/general-settings/smtp-settings/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Configure New SMTP Server</a></td>
                        <?php if (!$smtpSettings['default_ubirimi_server_flag']): ?>
                            <td><a href="" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <?php endif ?>
                        <td><a href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>
            <?php if ($smtpSettings): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="400px" valign="top">Name</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td valign="top">
                                <span><?php echo $smtpSettings['name'] ?></span>
                                <br />
                                <span><?php echo $smtpSettings['description'] ?></span>
                            </td>
                            <td valign="top">
                                <table>
                                    <tr>
                                        <td style="border: none;">From:</td>
                                        <td style="border: none;"><?php echo $smtpSettings['from_address'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">Prefix:</td>
                                        <td style="border: none;"><?php echo $smtpSettings['email_prefix'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">Host:</td>
                                        <td style="border: none;"><?php echo $smtpSettings['hostname'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">SMTP Port:</td>
                                        <td style="border: none;"><?php echo $smtpSettings['port'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">Username:</td>
                                        <td style="border: none;"><?php echo $smtpSettings['username'] ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="infoBox">There is no SMTP Server configured.</div>
            <?php endif ?>
            <input type="hidden" value="<?php echo $smtpSettings['id'] ?>" id="smtp_id" />
            <div class="ubirimiModalDialog" id="modalDeleteSMTPServer"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>