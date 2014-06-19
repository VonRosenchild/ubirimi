<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <form name="edit_perm_role" action="/yongo/administration/role/edit/<?php echo $permissionRoleId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/yongo/administration/roles">Roles</a> > <?php echo $perm_role['name'] ?> > Edit Permission role
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php if ($perm_role && $perm_role['name']) echo $perm_role['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($alreadyExists): ?>
                            <br />
                            <div class="error">A permission role with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php if ($perm_role && $perm_role['description']) echo $perm_role['description']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_edit_perm_role" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Role</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/roles">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>