<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php if ($isSVNAdministrator): ?>
        <?php Util::renderBreadCrumb('SVN Repositories > Create SVN Repository'); ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if ($isSVNAdministrator): ?>
            <form name="add_svn_repository" action="/svn-hosting/administration/repository/add" method="post">

                <div class="infoBox">Allow 2 minutes for your repository to become active</div>
                <table width="100%">
                    <tr>
                        <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
                            <?php if ($emptyName): ?>
                                <br />
                                <div class="error">The name of the SVN repository can not be empty.</div>
                            <?php elseif ($duplicateName): ?>
                                <br />
                                <div class="error">Duplicate SVN repository name. Please choose another name.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Code <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($code)) echo $code ?>" name="code"/>
                            <?php if ($emptyCode): ?>
                                <br />
                                <div class="error">The code can not be empty.</div>
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
                                <button type="submit" name="confirm_new_svn_repository" class="btn ubirimi-btn"><i class="icon-plus"></i> Create SVN repository</button>
                                <a class="btn ubirimi-btn" href="/svn-hosting/administration/all-repositories">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <div class="infoBox">You do not have the privileges to access this page.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>