<?php
    require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_topMenu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        Admin Home > Apache Logs > Overview
                    </div>
                </td>
            </tr>
        </table>

        <?php require_once __DIR__ . '/_menu.php' ?>

        <?php echo str_replace("\n", '<br /><br />', $output) ?>
        <?php require_once __DIR__ . '/_menu.php' ?>
    </div>
</body>