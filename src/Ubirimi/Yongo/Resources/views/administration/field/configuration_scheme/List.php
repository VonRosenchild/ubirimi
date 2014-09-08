<?php
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        Field Configuration Schemes
                    </div>
                </td>
            </tr>
        </table>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/custom-fields">Custom Fields</a></li>
            <li><a href="/yongo/administration/field-configurations">Field Configurations</a></li>
            <li class="active"><a href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/field-configuration/add-scheme" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Field Configuration Scheme</a></td>
                <?php if ($fieldConfigurationSchemes): ?>
                    <td><a id="btnEditFieldConfigurationSchemeMetadata" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnEditFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                    <td><a id="btnCopyFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>
        <div class="infoBox">
            Field Configuration Schemes map <a href="/yongo/administration/field-configurations">Field Configurations</a> to issue types. A Field Configuration Scheme can be associated with one or more projects, making issues in these projects use the Field Configuration mapped to their issue type.
        </div>
        <?php if ($fieldConfigurationSchemes): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Projects</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($fieldConfigurationScheme = $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $fieldConfigurationScheme['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $fieldConfigurationScheme['id'] ?>" />
                        </td>
                        <td>
                            <a href="/yongo/administration/field-configuration/scheme/edit/<?php echo $fieldConfigurationScheme['id'] ?>"><?php echo $fieldConfigurationScheme['name'] ?></a>
                            <div class="smallDescription"><?php echo $fieldConfigurationScheme['description'] ?></div>
                        </td>
                        <td width="500px">
                            <?php $projects = Project::getByIssueTypeFieldConfigurationScheme($clientId, $fieldConfigurationScheme['id']) ?>
                            <?php if ($projects): ?>
                                <ul>
                                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                    <li><a href="/yongo/administration/project/<?php echo $project['id'] ?>"><?php echo $project['name'] ?></a></li>
                                <?php endwhile ?>
                                </ul>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfigurationScheme['id'] ?>" value="0" />
                            <?php else: ?>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfigurationScheme['id'] ?>" value="1" />
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div id="deleteIssueSetting"></div>
            <input type="hidden" value="type" id="setting_type" />
        <?php else: ?>
            <div class="messageGreen">There are no field configuration schemes defined.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteFieldConfigurationScheme"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>