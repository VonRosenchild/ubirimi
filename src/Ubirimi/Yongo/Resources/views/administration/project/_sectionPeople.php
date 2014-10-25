<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

?>
<table width="100%">
    <tr>
        <td id="sectPeople" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">People</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentPeople">
                <tr>
                    <td>
                        <span>Ubirimi enables you to allocate particular people to specific roles in your project. Roles are used when defining other settings, like notifications and permissions.</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <div>Project Lead: <?php echo LinkHelper::getUserProfileLink($project['lead_id'], SystemProduct::SYS_PRODUCT_YONGO, $project['first_name'], $project['last_name']) ?></div>
                        <div>Default Assignee: Project Lead</div>
                        <div>Roles: <a href="/yongo/administration/project/people/<?php echo $projectId ?>">View Project Roles</a></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>