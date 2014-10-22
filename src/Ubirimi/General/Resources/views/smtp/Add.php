<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>

<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasClientAdministrationPermission()): ?>
        <?php Util::renderBreadCrumb('Mail > Add SMTP Settings') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>

        <form name="add_smtp_server" action="/general-settings/smtp-settings/add" method="post">
            <div class="infoBox">Use this page to add a new SMTP mail server. This server will be used to send all outgoing mail from Ubirimi.</div>
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyName): ?>
                        <div class="error">The name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">From address <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($fromAddress)) echo $fromAddress; ?>" name="from_address" />
                        <?php if ($emptyFromAddress): ?>
                        <div class="error">The from address can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Email prefix <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($emailPrefix)) echo $emailPrefix; ?>" name="email_prefix" />
                        <?php if ($emptyEmailPrefix): ?>
                        <div class="error">The email prefix can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Protocol <span class="error">*</span></td>
                    <td>
                        <select name="protocol" class="select2InputSmall">
                            <option value="1">SMTP</option>
                            <option value="2">SECURE_SMTP</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Hostname <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($hostname)) echo $hostname; ?>" name="hostname" />
                        <?php if ($emptyHostname): ?>
                        <div class="error">The hostname can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">SMTP Port</td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($port)) echo $port; ?>" name="port" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">Timeout</td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($timeout)) echo $timeout; ?>" name="timeout" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">TLS</td>
                    <td align="left">
                        <input type="checkbox" name="tls" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">Username</td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($username)) echo $username; ?>" name="username" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">Password</td>
                    <td>
                        <input class="inputText" type="password" value="<?php if (isset($password)) echo $password; ?>" name="password" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_smtp" class="btn ubirimi-btn">Add SMTP Server</button>
                            <a class="btn ubirimi-btn" href="/general-settings/smtp-settings">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>