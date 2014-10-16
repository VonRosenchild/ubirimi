<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">User Preferences > Edit</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <form name="update_settings" method="post" action="/yongo/administration/user-preference/edit">
            <table width="100%">
                <tr>
                    <td width="400">Number of issues displayed per Issue navigator page</td>
                    <td>
                        <input type="text"
                               class="inputText"
                               style="width: 50px;"
                               value="<?php echo $settings['issues_per_page'] ?>"
                               name="issues_per_page" />
                    </td>
                </tr>
                <tr>
                    <td width="400">Notify users of their own changes?</td>
                    <td>
                        <select class="select2InputSmall" style="width: 80px" name="notify_own_changes">
                            <option <?php if ($settings['notify_own_changes_flag']) echo 'selected="selected"' ?> value="1">YES</option>
                            <option <?php if (!$settings['notify_own_changes_flag']) echo 'selected="selected"' ?> value="0">NO</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn ubirimi-btn" name="edit_settings">Update Settings</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/user-preference">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>