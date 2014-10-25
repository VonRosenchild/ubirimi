<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a> > ' . $issueTypeScreenScheme['name'] . ' > Copy') ?>
    <div class="pageContent">
        <form name="form_copy_issue_type_screen_scheme" action="/yongo/administration/screen/scheme-issue-type/copy/<?php echo $issueTypeScreenSchemeId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $issueTypeScreenScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The issue type screen scheme name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">An issue type screen scheme with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $issueTypeScreenScheme['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="copy_issue_type_screen_scheme" class="btn ubirimi-btn">Copy Issue Type Screen Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/screens/issue-types">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>