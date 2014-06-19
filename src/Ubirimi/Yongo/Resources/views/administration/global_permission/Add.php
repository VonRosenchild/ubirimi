<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <form id="form_add_global_permission" name="add_global_permission" action="/yongo/administration/global-permission/add" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/global-permissions">Globals Permissions</a> > Create Permission') ?>
            <table width="100%">
                <tr>
                    <td width="100">Permission</td>
                    <td>
                        <select class="inputTextCombo" name="permission">
                            <?php while ($permission = $globalPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $permission['id'] ?>"><?php echo $permission['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Group</td>
                    <td>
                        <select class="inputTextCombo" name="group">
                            <?php while ($group = $allGroups->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" class="btn ubirimi-btn" name="confirm_new_permission">Add Permission</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/global-permissions">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>