<?php
    require_once __DIR__ . '/../../_header.php';
?>

<body>

    <?php require_once __DIR__ . '/../../_menu.php' ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > <?php echo $project['name'] ?> > <a class="linkNoUnderline" href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a> > Edit Version
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_release" action="/yongo/administration/project/version/edit/<?php echo $versionId; ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $version['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                        <br />
                        <div class="error">The name can not be empty.</div>
                        <?php elseif ($alreadyExists): ?>
                        <br />
                        <div class="error">A release with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td><textarea name="description" class="inputTextAreaLarge"><?php echo $version['description'] ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_release" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Version</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/versions/<?php echo $projectId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>