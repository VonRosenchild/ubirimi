<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > ' . $project['name'] . ' > Edit Project';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="add_project" action="/yongo/administration/project/edit/<?php echo $projectId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name</td>
                    <td>
                        <input class="inputText" type="text" value="<?php if ($project && $project['name']) echo $project['name']; ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the project can not be empty.</div>
                        <?php elseif ($duplicate_name): ?>
                            <br />
                            <div class="error">Duplicate project name. Please choose another name.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Code</td>
                    <td>
                        <input class="inputText" type="text" value="<?php if ($project && $project['code']) echo $project['code']; ?>" name="code"/>
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
                        <textarea class="inputTextAreaLarge" name="description"><?php if ($project && $project['description']) echo $project['description']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Issue Type Scheme</td>
                    <td>
                        <select name="issue_type_scheme" class="select2InputSmall">
                            <?php while ($scheme = $issueTypeScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($scheme['id'] == $project['issue_type_scheme_id']) echo 'selected="selected"' ?> value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Project Category</td>
                    <td>
                        <select name="project_category" class="select2InputSmall">
                            <option <?php if (null == $project['project_category_id']) echo 'selected="selected"' ?> value="-1">None</option>
                            <?php if ($projectCategories): ?>
                                <?php while ($category = $projectCategories->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option <?php if ($category['id'] == $project['project_category_id']) echo 'selected="selected"' ?> value="<?php echo $category['id'] ?>"><?php echo $category['name']; ?></option>
                                <?php endwhile ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Workflow Scheme</td>
                    <td>
                        <select name="workflow_scheme" class="select2InputSmall">
                            <?php while ($scheme = $workflowScheme->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($scheme['id'] == $project['workflow_scheme_id']) echo 'selected="selected"' ?> value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Lead</td>
                    <td>
                        <select name="lead" class="select2InputSmall">
                            <?php while ($user = $leadUsers->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($project['lead_id'] == $user['id']) echo 'selected="selected"' ?>
                                    value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_edit_project" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Project</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/projects">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>