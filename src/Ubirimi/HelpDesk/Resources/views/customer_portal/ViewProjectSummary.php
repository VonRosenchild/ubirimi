<?php
    require_once __DIR__ . '/_header.php';
?>

<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Summary</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li <?php if ($menuProjectCategory == 'summary'): ?>class="active"<?php endif ?>>
                <a href="/helpdesk/customer-portal/project/<?php echo $projectId ?>" title="Summary">Summary</a>
            </li>
            <li <?php if ($menuProjectCategory == 'issues'): ?>class="active" <?php endif ?>>
                <a href="/helpdesk/customer-portal/project/issues/<?php echo $projectId ?>" title="Issues">Issues</a>
            </li>
        </ul>

        <table width="100%">
            <tr>
                <td valign="top" width="50%">
                    <table width="100%">
                        <tr>
                            <td id="sectIssueTypes" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Description</span></td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td><span class="textLabel">Lead:</span></td>
                                        <td><?php echo $project['first_name'] . ' ' . $project['last_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><span class="textLabel">Code:</span></td>
                                        <td><?php echo $project['code'] ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td id="sectIssueTypes" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Issues: 30 Day Summary</span></td>
                        </tr>

                        <tr>
                            <td>
                                <div id="chart_created_resolved" style="height: 300px"></div>
                            </td>
                        </tr>
                    </table>

                </td>
                <td width="20px"></td>
                <td valign="top">
                    <table width="100%">
                        <tr>
                            <td id="sectPeople" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Activity Stream</span></td>
                        </tr>
                        <tr>
                            <td id="project_activity_stream">

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>