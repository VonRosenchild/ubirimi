<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%" class="">
            <tr>
                <td>
                    <div class="headerPageText"><a class="linkNoUnderline" href="/svn-hosting/administration/administrators">SVN Administrators</a> > Add Administrator </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <form name="add_svn_administrator" action="/svn-hosting/administration/add-administrator" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="130">Available Users <span class="mandatory">*</span></td>
                    <td>
                        <select name="user[]" size="10" class="inputTextCombo" multiple="multiple">
                            <?php while ($regularUsers && $user = $regularUsers->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                        <?php if ($noUsersSelected): ?>
                            <div class="error">Please select a user.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_new_svn_administrator" class="btn ubirimi-btn">Add SVN Administrators</button>
                            <a class="btn ubirimi-btn" href="/svn-hosting/administration/administrators">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>