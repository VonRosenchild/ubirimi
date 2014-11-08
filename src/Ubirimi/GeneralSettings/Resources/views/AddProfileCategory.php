<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="pageContent">
        <form name="add_profile_category" action="/general-settings/users/profile-manager/category/add" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/general-settings/users/profile-manager">Profile Manager</a> > Create Profile Category') ?>

            <table>
                <tr>
                    <td valign="top" width="150">Name <span class="error">*</span></td>
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
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <button type="submit" name="new_profile_category" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Profile Category</button>
                        <a class="btn ubirimi-btn" href="/general-settings/users/profile-manager">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>
</html>