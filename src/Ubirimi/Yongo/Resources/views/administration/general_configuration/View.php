<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">General Configuration</div>
                </td>
            </tr>
        </table>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/general-configuration/edit" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Configuration</a></td>
            </tr>
        </table>

        <div class="headerPageText">Options</div>

        <table class="table table-hover table-condensed">
            <tr>
                <td width="30%">Allow unassigned issues</td>
                <td><?php if ($clientYongoSettings['allow_unassigned_issues_flag']) echo 'ON'; else echo 'OFF' ?></td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>