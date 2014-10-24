<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

?>
<table width="100%">
    <tr>
        <td id="sectNotifications" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Notifications</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentNotifications">
                <tr>
                    <td>
                        <span>Ubirimi can notify the appropriate people of particular events in your project, e.g. "Issue Commented". You can choose specific people, groups, or roles to receive notifications.</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <span>Scheme:</span>
                        <?php
                            $notificationScheme = UbirimiContainer::get()['repository']->get(NotificationScheme::class)->getMetaDataById($project['notification_scheme_id']);
                        ?>
                        <span><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>"><?php echo $notificationScheme['name'] ?></a></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>