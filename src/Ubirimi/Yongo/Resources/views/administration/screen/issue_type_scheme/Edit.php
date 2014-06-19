<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <form name="edit_issue_type_screen_scheme_metadata" action="/yongo/administration/screen/edit-scheme-issue-type/<?php echo $issueTypeScreenSchemeId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a> > <?php echo $issueTypeScreenScheme['name'] ?> > Update
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $issueTypeScreenScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The issue type screen scheme name can not be empty.</div>
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
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <button type="submit" name="edit_issue_type_screen_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Type Screen Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/screens/issue-types">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>