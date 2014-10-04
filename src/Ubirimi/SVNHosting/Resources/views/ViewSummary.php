<?php
    use Ubirimi\Container\UbirimiContainer;
    use ubirimi\svn\SVNLog;
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_menu.php'; ?>

<div class="pageContent">
    <?php if ($svnRepo): ?>
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText"><a class="linkNoUnderline" href="/svn-hosting/repositories">Repositories</a> > <a class="linkNoUnderline" href="/svn-hosting/repository/<?php echo $svnRepo['id'] ?>"><?php echo $svnRepo['name'] ?></a> > Summary</div>
                </td>
            </tr>
        </table>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/svn-hosting/repository/<?php echo $svnRepoId ?>">Summary</a></li>
            <li><a href="/svn-hosting/repository/my-settings/<?php echo $svnRepoId ?>">My Settings</a></li>
            <li><a href="/svn-hosting/repository/users/<?php echo $svnRepoId ?>">Users</a></li>
        </ul>

        <table width="100%">
            <tr>
                <td valign="top" width="50%">
                    <?php require_once __DIR__ . '/administration/_summary.php' ?>

                </td>
                <td valign="top">
                    <table width="100%">
                        <tr>
                            <td id="sectPeople" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Activity Stream (last 25 commits)</span></td>
                        </tr>
                        <tr>
                            <td id="svn_activity_stream">
                                <?php
                                    $log = null;
                                    try {
                                        $log = SVNLog::log(UbirimiContainer::get()['subversion.path'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($svnRepo['name']));
                                    } catch (Exception $e) {

                                    }
                                ?>

                                <table>
                                    <?php if ($log): ?>
                                        <?php $i = 0 ?>

                                        <?php foreach ($log as $key => $revision): ?>
                                            <tr>
                                                <td width="90">Revision <?php echo $key ?></td>
                                                <td width="120">by
                                                    <?php echo $revision['author'] ?>
                                                </td>
                                                <td> - <?php echo $revision['msg'] ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <input type="hidden" value="<?php echo $svnRepoId ?>" id="svn_repo_id" />
    <?php else: ?>
        <div class="infoBox">SVN Repository not found.</div>
    <?php endif ?>
</div>
<?php require_once __DIR__ . '/_footer.php' ?>
</body>