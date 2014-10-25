<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breachCrumb = '<a class="linkNoUnderline" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a> > ' . $issueSecurityScheme['name'] . ' > Create Level';
        Util::renderBreadCrumb($breachCrumb);
    ?>
    <div class="pageContent">
        <form name="add_issue_security_scheme_level" action="/yongo/administration/issue-security-scheme/level/add/<?php echo $issueSecuritySchemeId ?>" method="post">


            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
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
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_issue_security_scheme_level" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Security Scheme Level</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-security-scheme-levels/<?php echo $issueSecuritySchemeId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>