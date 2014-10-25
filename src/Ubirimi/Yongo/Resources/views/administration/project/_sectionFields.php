<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

?>
<table width="100%">
    <tr>
        <td id="sectFields" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Fields</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentFields">
                <tr>
                    <td><span>Different issues can have different information fields. A field configuration defines how fields behave for the project, e.g. required/optional; hidden/visible.</span></td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <?php
                            $issueTypeFieldConfigurationScheme = UbirimiContainer::get()['repository']->get(FieldConfigurationScheme::class)->getMetaDataById($project['issue_type_field_configuration_id']);
                            $fieldConfigurations = UbirimiContainer::get()['repository']->get(FieldConfigurationScheme::class)->getFieldConfigurations($project['issue_type_field_configuration_id']);
                        ?>
                        <span><a href="/yongo/administration/project/fields/<?php echo $project['id'] ?>"><?php echo $issueTypeFieldConfigurationScheme['name'] ?></a></span>
                        <?php while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><a href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfiguration['id'] ?>?source=project&project_id=<?php echo $project['id'] ?>"><?php echo $fieldConfiguration['name'] ?></a></div>
                        <?php endwhile ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>