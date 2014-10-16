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
                        <a class="linkNoUnderline" href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a> > <?php echo $fieldConfigurationScheme['name'] ?> > Configure
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/field-configurations/schemes" class="btn ubirimi-btn">Go Back</a></td>
                <td><a id="btnEditFieldConfigurationSchemeData" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th width="200">Issue Type</th>
                    <th>Field Configuration</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($data = $fieldConfigurationSchemeData->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $data['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $data['id'] ?>" />
                    </td>
                    <td><?php echo ucfirst($data['issue_type_name']); ?></td>
                    <td><?php echo $data['field_configuration_name']; ?></td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>