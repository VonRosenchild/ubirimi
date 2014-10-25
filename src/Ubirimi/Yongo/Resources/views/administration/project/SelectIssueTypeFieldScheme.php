<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > ' . $project['name'] . '> Field Configuration Scheme > Select a Different Scheme</div>';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="select_field_conf_scheme" method="post" action="/yongo/administration/project/select-issue-type-field-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td width="200">Field Configuration Scheme</td>
                    <td>
                        <select name="issue_type_field_scheme" class="select2InputMedium">
                            <?php while ($fieldConfigurationScheme = $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $fieldConfigurationScheme['id'] ?>"><?php echo $fieldConfigurationScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="associate" class="btn ubirimi-btn">Associate</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/fields/<?php echo $projectId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>