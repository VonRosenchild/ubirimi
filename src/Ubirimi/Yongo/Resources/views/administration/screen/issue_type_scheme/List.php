<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Issue Type Screen Schemes') ?>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/screens">Screens</a></li>
            <li><a href="/yongo/administration/screens/schemes">Screen Schemes</a></li>
            <li class="active"><a href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/screen/add-scheme-issue-type" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Type Screen Scheme</a></td>
                <td><a id="btnEditIssueTypeScreenScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnConfigureIssueTypeScreenScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                <td><a id="btnCopyIssueTypeScreenScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                <td><a id="btnDeleteIssueTypeScreenScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <div class="infoBox">
            <div>An Issue Type Screen Scheme allows you to choose what <a href="/yongo/administration/screens/schemes">Screen Scheme</a> is used for each issue type.</div>
            <div>Then, an Issue Type Screen Scheme can be associated with one or more projects, to specify what Screen Scheme, and hence what <a href="/yongo/administration/screens">Screen</a> should be used for a particular issue operation for the projects' issues.</div>
        </div>
        <?php if ($issueTypeScreenSchemes): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th align="left">Projects</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($scheme = $issueTypeScreenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $scheme['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>" /></td>
                            <td>
                                <a href="/yongo/administration/screen/configure-scheme-issue-type/<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></a>
                            </td>
                            <td width="500px">
                                <?php
                                    $projects = Project::getByIssueTypeScreenSchemeId($clientId, $scheme['id']);
                                    if ($projects) {
                                        echo '<ul>';
                                        while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                            echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                                        }
                                        echo '</ul>';
                                        echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="0">';
                                    } else {
                                        echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="1">';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no issue type screen schemes defined.</div>
        <?php endif ?>
    </div>
        <div class="ubirimiModalDialog" id="modalDeleteIssueTypeScreenScheme"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php endif ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>