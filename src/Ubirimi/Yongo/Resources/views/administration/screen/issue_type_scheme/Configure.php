<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a> > <?php echo $issueTypeScreenScheme['name'] ?> > Configure
                    </div>
                </td>
            </tr>
        </table>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a class="btn ubirimi-btn" href="/yongo/administration/screens/issue-types">Go Back</a></td>
                <td><a id="btnEditIssueTypeScreenSchemeData" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th width="200">Issue Type</th>
                    <th>Screen Scheme</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($data = $issueTypeScreenSchemeData->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $data['id'] ?>">
                        <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $data['id'] ?>" /></td>
                        <td><?php echo ucfirst($data['issue_type_name']); ?></td>
                        <td><?php echo $data['screen_scheme_name']; ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>