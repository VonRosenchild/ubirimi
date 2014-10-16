<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a> > ' . $issueSecurityScheme['name'] .' > Edit';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">

        <form name="edit_issue_security_scheme_metadata" action="/yongo/administration/issue-security-scheme/edit/<?php echo $issueSecuritySchemeId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $issueSecurityScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The issue security scheme name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $issueSecurityScheme['description'] ?></textarea>
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
                            <button type="submit" name="edit_issue_security_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Security Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-security-schemes">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>