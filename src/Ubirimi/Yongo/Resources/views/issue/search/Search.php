<?php
    require_once __DIR__ . '/../../_header.php';

    $parseURLData = parse_url($_SERVER['REQUEST_URI']);
    $query = isset($parseURLData['query']) ? $parseURLData['query'] : '';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">

        <?php if (!$projectsForBrowsing): ?>
            <div class="messageGreen">There are no projects were you have the permission to browse issues.</div>
        <?php else: ?>
            <table width="100%" class="headerPageBackground" cellpadding="0" cellspacing="0">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="10%">
                        <div class="headerPageText" style="left: 0; right: auto;">Filters</div>
                    </td>
                    <td width="10%">
                        <div class="headerPageText" style="left: 0; right: auto;">Criteria</div>
                    </td>
                    <td width="80%">
                        <?php require_once __DIR__ . '/_buttonsOptions.php' ?>
                        <div class="headerPageText" style="margin-right: 20px;">Search results</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="separationVertical"></div>
                    </td>
                </tr>
                <tr>
                    <td width="10%" valign="top">
                        <div class="pageContent" style="padding: 0px; margin: 0px; margin-right: 10px; border: 0px;">
                            <table width="100%" class="table table-hover table-condensed">
                                <tr>
                                    <td>
                                        <a href="/yongo/issue/search?project=<?php echo implode('|', $projectsForBrowsing) ?>&resolution=-2&assignee=<?php echo $loggedInUserId ?>">My Open Issues</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="/yongo/issue/search?project=<?php echo implode('|', $projectsForBrowsing) ?>&reporter=<?php echo $loggedInUserId ?>">Reported By Me</a>
                                    </td>
                                </tr>
                                <?php while ($filters && $filter = $filters->fetch_array(MYSQLI_ASSOC)): ?>
                                    <tr>
                                        <td <?php if ($filter['id'] == $getFilter) echo 'style="background-color: #f5f5f5;"' ?>>
                                            <a href="/yongo/issue/search?filter=<?php echo $filter['id'] ?>&<?php echo $filter['definition'] ?>"><?php echo $filter['name'] ?></a>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                            </table>
                        </div>
                    </td>
                    <td width="10%" valign="top">
                        <div class="pageContent" style="padding: 10px; margin: 0px; margin-right: 10px; border-radius: 0px;">
                            <form name="search_form" action="/yongo/issue/search<?php if ($extraParametersURL) echo '?' . $extraParametersURL; ?>" method="post">
                                <table cellpadding="0" cellspacing="2" border="0" width="200px">
                                    <?php require_once __DIR__ . '/_filterMain.php' ?>
                                    <?php require_once __DIR__ . '/_filterProject.php' ?>
                                    <?php require_once __DIR__ . '/_filterType.php' ?>

                                    <tr id="contentSearchIssueProperties">
                                        <td>
                                            <?php require_once __DIR__ . '/_filterStatus.php' ?>
                                            <?php require_once __DIR__ . '/_filterPriority.php' ?>
                                            <?php require_once __DIR__ . '/_filterResolution.php' ?>
                                            <?php require_once __DIR__ . '/_filterAssignee.php' ?>
                                            <?php require_once __DIR__ . '/_filterReporter.php' ?>
                                            <?php require_once __DIR__ . '/_filterComponent.php' ?>
                                            <?php require_once __DIR__ . '/_filterAffectsVersion.php' ?>
                                            <?php require_once __DIR__ . '/_filterFixVersion.php' ?>
                                            <?php require_once __DIR__ . '/_filterDates.php' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <div style="height: 8px"></div>

                                            <input class="btn ubirimi-btn"
                                                   type="button"
                                                   value="<?php if ($getFilter) echo 'Update'; else echo 'Save Filter' ?>"
                                                   id="btn_save_filter" name="btn_save_filter"/>
                                            <input class="btn ubirimi-btn" type="submit" value="Search" name="search"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </td>
                    <td width="80%" valign="top">
                        <?php
                            $urlIssuePrefix = '/yongo/issue/';
                            require_once __DIR__ . '/_listResult.php'
                        ?>
                    </td>
                </tr>
            </table>
            <div id="saveFilterModal"></div>
            <input type="hidden" value="<?php echo $getFilter ?>" id="entity_id"/>
            <input type="hidden" value="<?php echo $projectIds[0] ?>" id="project_id"/>
            <input type="hidden" value="<?php echo urldecode($query) ?>" id="filter_url"/>
        <?php endif ?>
    </div>
    <?php
        $parseData = parse_url($_SERVER['REQUEST_URI']);

        if (isset($parseData['query'])) {
            $session->set('last_search_parameters', $parseData['query']);
        } else {
            $session->remove('last_search_parameters');
        }
    ?>

    <?php require_once __DIR__ . '/_chooseDisplayColumns.php' ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>

    <?php if ($projectsForBrowsing): ?>
        <div id="contentMenuIssueSearchOptions"></div>
        <div id="duplicateIssueModal"></div>
        <div id="modalEditIssue"></div>
    <?php endif ?>
    <input type="hidden" value="context_search" id="context_search" />
</body>