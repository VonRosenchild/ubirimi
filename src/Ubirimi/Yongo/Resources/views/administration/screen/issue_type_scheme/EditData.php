<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a> > <?php echo $issueTypeScreenSchemeMetaData['name'] ?> > Configure
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <form name="edit_screen_metadata" action="/yongo/administration/screen/edit-scheme-issue-type-data/<?php echo $issueTypeScreenSchemeDataId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Issue Type</td>
                    <td>
                        <?php echo ucfirst($issueTypeScreenSchemeData['issue_type_name']) ?>
                    </td>
                </tr>
                <tr>
                    <td>Screen Scheme</td>
                    <td>
                        <select name="screen_scheme" class="select2InputSmall">
                            <?php while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $screenScheme['id'] ?>"><?php echo $screenScheme['name'] ?></option>
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
                            <button type="submit" name="edit_issue_type_screen_scheme_data" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Type Screen Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/screen/configure-scheme-issue-type/<?php echo $screenSchemeId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $issueTypeScreenSchemeData['issue_type_id'] ?>" name="issue_type" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>