<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > ' .
            '<a class="linkNoUnderline" href="/yongo/administration/project/' . $projectId . '">' . $project['name'] . '</a> > ' .
            '<a class="linkNoUnderline" href="/yongo/administration/project/components/' . $projectId . '">Components</a> > ' .
            $parentComponent['name'] . ' > Create Sub-Component';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="add_component" action="/yongo/administration/project/subcomponent/add/<?php echo $projectId ?>/<?php echo $parentComponentId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="120" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <br/>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($alreadyExists): ?>
                            <br/>
                            <div class="error">A component with the same name already exists.</div>
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
                    <td>Leader</td>
                    <td>
                        <select name="leader" class="select2InputSmall">
                            <option value="-1">No one</option>
                            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if (isset($leader) && $leader == $user['id']) echo 'selected="selected"' ?> value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_new_component" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Component
                            </button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/components/<?php echo $projectId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $parentComponentId ?>" name="parent_component_id" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>