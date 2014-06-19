<?php

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <div class="noPrint">
        <div style="margin: 10px;" class="noPrint">
            <a class="noPrint btn ubirimi-btn" href="/yongo/issue/search?<?php echo $parseURLData['query'] ?>">Back to previous view</a>
        </div>
    </div>
    <?php if (isset($issuesCount) && $issuesCount > 0): ?>
        <?php
        $htmlOutputIssueRow = '';
        $arrayIds = array();
        while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            echo '<div class="pageContent">';
                require __DIR__ . '/../_viewPrint.php';
            echo '</div>';
            echo '<div align="center">&sect;&sect;&sect;</div>';
        }
        ?>
    <?php else: ?>
        <div>
            <table class="table table-hover table-condensed">
                <td colspan="<?php echo count($columns) ?>">No issues were found to match your search</td>
            </table>
        </div>
    <?php endif ?>

</body>