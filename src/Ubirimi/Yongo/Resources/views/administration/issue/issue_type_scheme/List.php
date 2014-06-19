<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Issue Type Schemes') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/issue-types">Issue Types</a></li>
                <li class="active"><a href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a></li>
                <li><a href="/yongo/administration/issue-sub-tasks">Sub-Tasks</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/add-type-scheme/project" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Type Scheme</a></td>
                    <td><a id="btnEditIssueTypeScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnCopyIssueTypeScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteIssueTypeScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
            <div class="infoBox">An issue type scheme determines which issue types will be available to a set of projects. It also allows to specify the order in which the issue types are presented in the user interface.</div>
            <?php if ($issueTypeSchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Options</th>
                            <th>Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scheme = $issueTypeSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $scheme['id'] ?>">
                                <td style="vertical-align: top">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>" />
                                    <?php echo $scheme['name'] ?>
                                    <div class="smallDescription" style="padding-left: 25px;"><?php echo $scheme['description'] ?></div>
                                </td>
                                <td style="vertical-align: top">
                                    <?php
                                        $dataIssueTypeScheme = IssueTypeScheme::getDataById($scheme['id']);
                                        if ($dataIssueTypeScheme) {
                                            while ($data = $dataIssueTypeScheme->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<div>';
                                                    echo '<img height="16px" src="/yongo/img/issue_type/' . $data['issue_type_icon_name'] . '" />';
                                                    echo '<span> ' . $data['name'] . '</span>';
                                                echo '</div>';
                                            }
                                        }
                                    ?>
                                </td>
                                <td valign="top">
                                    <?php
                                        $projects = Project::getByIssueTypeScheme($scheme['id']);
                                        if ($projects) {
                                                while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                                    echo '&#8226; <a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a>';
                                                    echo '<br />';
                                                }

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
                <div class="messageGreen">There are no issue type schemes defined</div>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <div id="modalDeleteIssueTypeScheme"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>