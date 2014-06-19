<?php

require_once __DIR__ . '/../_header.php'
?>
<body style="background-color: #ffffff">
    <table width="100%">
        <tr>
            <td>
                <div class="headerPageText">
                    <?php echo $issue['project_code'] . '-' . $issue['nr'] ?>
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_viewPrint.php' ?>

</body>