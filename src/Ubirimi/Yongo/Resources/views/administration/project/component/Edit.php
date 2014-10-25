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
                        Projects > <?php echo $project['name'] ?> > <a class="linkNoUnderline" href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a> > Edit component
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_component" action="/yongo/administration/project/component/edit/<?php echo $componentId; ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $component['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($alreadyExists): ?>
                            <br />
                            <div class="error">A component with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $component['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Leader</td>
                    <td>
                        <select name="leader" class="inputTextCombo">
                            <option value="-1" <?php if (null == $component['leader_id']) echo 'selected="selected"' ?>>No one</option>
                            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($user['id'] == $component['leader_id']) echo 'selected="selected"' ?> value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
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
                            <button type="submit" name="edit_component" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Component</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/components/<?php echo $projectId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>