<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php echo Util::renderBreadCrumb('Search') ?>
    <div class="pageContent">

        <?php if (!$projectsForBrowsing): ?>
            <div class="messageGreen">There are no projects were you have the permission to browse issues.</div>
        <?php else: ?>
        <table width="100%" class="headerPageBackground" cellpadding="0" cellspacing="0">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <div class="headerPageText" style="left: 0; right: auto;">Criteria</div>
                    </td>
                    <td width="100%">
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
                        <div class="pageContent" style="padding: 10px; margin: 0px; margin-right: 10px; border-radius: 0px;">
                            <form name="search_form" action="/helpdesk/customer-portal/tickets" method="post">
                                <table cellpadding="0" cellspacing="2" border="0" width="200px">
                                    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterProject.php' ?>
                                    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterType.php' ?>

                                    <tr id="contentSearchIssueProperties">
                                        <td>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterStatus.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterPriority.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterResolution.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterAssignee.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterReporter.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterComponent.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterAffectsVersion.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterFixVersion.php' ?>
                                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_filterDates.php' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <div style="height: 8px"></div>
                                            <input class="btn ubirimi-btn" type="submit" value="Search" name="search"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </td>
                    <td width="80%" valign="top">
                        <?php
                            $urlIssuePrefix = '/helpdesk/customer-portal/ticket/';
                            require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_listResult.php';
                        ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $projectIds[0] ?>" id="project_id"/>
            <input type="hidden" value="<?php echo urldecode($query) ?>" id="filter_url"/>
            <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_chooseDisplayColumns.php' ?>

    <?php if ($projectsForBrowsing): ?>
        <div id="contentMenuIssueSearchOptions"></div>
        <div class="ubirimiModalDialog" id="modalEditIssue"></div>
    <?php endif ?>
    <input type="hidden" value="context_search" id="context_search" />
</body>