<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > ' . $project['name'] . ' > Notification Scheme > Select a Different Scheme</div>';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <div class="infoBox">This page allows you to associate a notification scheme with this project.</div>

        <form name="select_notification_scheme" method="post" action="/yongo/administration/project/notifications/select-project-notification-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td width="200">Notification Scheme</td>
                    <td>
                        <select name="perm_scheme" class="select2InputMedium">
                            <?php while ($notificationScheme = $notificationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($notificationScheme['id'] == $project['notification_scheme_id']) echo 'selected="selected"' ?> value="<?php echo $notificationScheme['id'] ?>"><?php echo $notificationScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="associate" class="btn ubirimi-btn">Associate</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>