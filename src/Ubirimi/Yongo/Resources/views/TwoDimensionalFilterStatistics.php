<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';

?>

<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Home > 2 Dimensional Filter Statistics') ?>

    <div class="pageContent">

        <?php require_once __DIR__ . '/_home_subtabs.php' ?>
        <div style="padding-top: 4px; padding-bottom: 4px"></div>
        <?php if ($allProjects == null && ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission)): ?>
            <div class="infoBox" style="margin-top: 4px;">
                <div>There are no projects created. You can create one by clicking <a class="linkNoUnderline" href="/yongo/administration/project/add"><b>here</b></a>.</div>
            </div>
        <?php endif ?>

        <div>
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active"><a href="#" title="2 Dimensional Filter Statistics">2 Dimensional Filter Statistics</a></li>
            </ul>
            <div>
                <div style="border: 1px solid #d6d6d6; border-top: none;">
                    <?php if (count($projectIdsNames)): ?>
                        <div style="padding: 4px">
                            <?php require_once __DIR__ . '/charts/ViewTwoDimensionalFilter.php'; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 8px;">There are no projects to display information for.</div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>