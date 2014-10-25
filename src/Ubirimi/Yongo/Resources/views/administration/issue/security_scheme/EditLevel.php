<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a> > ' . $issueSecurityScheme['name'] .' > ' . $issueSecuritySchemeLevel['name'] . ' >  Edit';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="edit_issue_security_scheme_level" action="/yongo/administration/issue-security-scheme-level/edit/<?php echo $issueSecuritySchemeLevelId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $issueSecuritySchemeLevel['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The issue security scheme level name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $issueSecuritySchemeLevel['description'] ?></textarea>
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
                            <button type="submit" name="edit_issue_security_scheme_level" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Security Scheme Level</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-security-scheme-levels/<?php echo $issueSecurityScheme['id'] ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>