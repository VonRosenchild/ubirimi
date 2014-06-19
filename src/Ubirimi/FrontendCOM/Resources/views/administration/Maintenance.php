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
                    Admin Home > Maintenance
                </div>
            </td>
        </tr>
    </table>
    <form name="maintenance" method="post" action="/administration/maintenance">
        <table>
            <tr>
                <td>Maintenance Server Message</td>
            </tr>
            <tr>
                <td>
                    <textarea class="inputTextAreaLarge" name="maintenance_message"><?php echo $serverSettings['maintenance_server_message'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" class="btn ubirimi-btn" value="Update" name="update"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
