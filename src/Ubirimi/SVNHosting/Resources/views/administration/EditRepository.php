<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/svn-hosting/administration/all-repositories" class="linkNoUnderline">SVN Repositories</a> > <?php echo $svnRepo['name'] ?> > Edit</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_svn_repository" action="/svn-hosting/administration/repository/edit/<?php echo $repoId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" readonly="readonly" value="<?php echo $name ?>" name="name"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Code <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($code)) echo $code ?>" name="code"/>
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
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_edit_svn_repository" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit SVN Repository</button>
                            <a class="btn ubirimi-btn" href="/svn-hosting/administration/all-repositories">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="repo_id" value="<?php echo $repoId ?>" />
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>