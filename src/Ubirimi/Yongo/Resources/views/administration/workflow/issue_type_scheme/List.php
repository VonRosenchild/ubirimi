<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\TypeScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Workflow Issue Type Schemes') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/workflows">Workflows</a></li>
                <li class="active"><a href="/yongo/administration/workflows/issue-type-schemes">Workflow Issue Type Schemes</a></li>
                <li><a href="/yongo/administration/workflows/schemes">Workflow Schemes</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/add-type-scheme/workflow" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Workflow Issue Type Scheme</a></td>
                    <td><a id="btnEditIssueTypeScheme" href="" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnCopyWorkflowIssueTypeScheme" href="/yongo/administration/issue-type-scheme/copy/3" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteWorkflowIssueTypeScheme" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
            <div class="infoBox">
                <div>Workflow Issue Type Schemes allow you to set what issue types will be used by a specific workflow.</div>
            </div>
            <?php if ($issueTypeSchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Issue Types</th>
                            <th>Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($scheme = $issueTypeSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $scheme['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>"/>
                            </td>
                            <td><?php echo $scheme['name']; ?></td>
                            <td width="300px">
                                <?php $schemeIssueTypes = TypeScheme::getDataById($scheme['id']) ?>
                                <ul>
                                    <?php while ($schemeIssueTypes && $issueType = $schemeIssueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                                        <li><?php echo $issueType['name'] ?></li>
                                    <?php endwhile ?>
                                </ul>
                            </td>
                            <td>
                                <?php $projects = $this->getRepository('yongo.project.project')->getByWorkflowIssueTypeScheme($clientId, $scheme['id']) ?>
                                <?php if ($projects): ?>
                                <ul>
                                    <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                    <li>
                                        <a href="/yongo/administration/project/<?php echo $project['id'] ?>"><?php echo $project['name'] ?></a>
                                    </li>
                                    <?php endwhile ?>
                                </ul>
                                <input type="hidden" id="delete_possible_<?php echo $scheme['id'] ?>" value="0" />
                                <?php else: ?>
                                <input type="hidden" id="delete_possible_<?php echo $scheme['id'] ?>" value="1" />
                                <?php endif ?>
                            </td>
                        </tr>
                    </tbody>
                    <?php endwhile ?>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no workflow issue type schemes defined.</div>
            <?php endif ?>
            <div class="ubirimiModalDialog" id="modalDeleteWorkflowIssueTypeScheme"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body></html>