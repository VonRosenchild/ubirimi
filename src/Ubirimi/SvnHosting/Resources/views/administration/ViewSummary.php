<?php

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">Administration > <a class="linkNoUnderline" href="/svn-hosting/administration/all-repositories">Repositories</a> > <?php echo $svnRepo['name'] ?></a> > Summary</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/svn-hosting/administration/repository/<?php echo $svnRepoId ?>">Summary</a></li>
            <li><a href="/svn-hosting/administration/repository/users/<?php echo $svnRepoId ?>">Users</a></li>
        </ul>

        <table width="100%">
            <tr>
                <td valign="top" width="50%">
                    <?php require_once __DIR__ . '/_summary.php' ?>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>