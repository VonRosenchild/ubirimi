<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <?php
        if ($forHelpDesk) {
            Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > Create Helpdesk Project');
        } else {
            Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > Create Project');
        }
    ?>

    <div class="pageContent">
        <form name="add_project" action="/yongo/administration/project/add<?php if ($forHelpDesk) echo '?helpdesk=true' ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input id="project_name_add" class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the project can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">Duplicate project name. Please choose another name.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Code <span class="mandatory">*</span></td>
                    <td>
                        <input style="text-transform: uppercase;" maxlength="5" class="inputText" type="text" value="<?php if (isset($code)) echo $code ?>" name="code"/>
                        <?php if ($emptyCode): ?>
                            <br />
                            <div class="error">The code of the project can not be empty.</div>
                        <?php elseif ($duplicateCode): ?>
                            <br />
                            <div class="error">Duplicate project code. Please choose another code.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Project Category</td>
                    <td>
                        <select name="project_category" class="select2InputSmall">
                            <option value="-1" selected="selected">None</option>
                            <?php if ($projectCategories): ?>
                                <?php while ($category = $projectCategories->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name']; ?></option>
                                <?php endwhile ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Issue Type Scheme</td>
                    <td>
                        <select name="issue_type_scheme" class="select2InputSmall">
                            <?php while ($scheme = $issueTypeScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Issue Type Screen Scheme</td>
                    <td>
                        <select name="issue_type_screen_scheme" class="select2InputSmall">
                            <?php while ($screenScheme = $issueTypeScreenScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $screenScheme['id'] ?>"><?php echo $screenScheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Field Configuration Scheme</td>
                    <td>
                        <select name="field_configuration_scheme" class="select2InputSmall">
                            <?php while ($fieldConfigurationScheme = $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $fieldConfigurationScheme['id'] ?>"><?php echo $fieldConfigurationScheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Workflow Scheme</td>
                    <td>
                        <select name="workflow_scheme" class="select2InputSmall">
                            <?php while ($scheme = $workflowScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Permission Scheme</td>
                    <td>
                        <select name="permission_scheme" class="select2InputSmall">
                            <?php while ($scheme = $permissionScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Notification Scheme</td>
                    <td>
                        <select name="notification_scheme" class="select2InputSmall">
                            <?php while ($scheme = $notificationScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Lead</td>
                    <td>
                        <select name="lead" class="select2InputSmall">
                            <?php while ($user = $leadUsers->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                            <?php endwhile ?>
                        </select>
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
                            <button type="submit" name="confirm_new_project" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Project</button>
                            <?php if ($forHelpDesk): ?>
                                <a class="btn ubirimi-btn" href="/helpdesk/all">Cancel</a>
                            <?php else: ?>
                                <a class="btn ubirimi-btn" href="/yongo/administration/projects">Cancel</a>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>