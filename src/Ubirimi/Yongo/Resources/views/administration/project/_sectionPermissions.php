<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

?>
<table width="100%">
    <tr>
        <td id="sectPermissionScheme" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Permissions</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentPermissionScheme">
                <tr>
                    <td>
                        <span>Project permissions allow you to control who can access your project, and what they can do, e.g. "Work on Issues".
                        Access to individual issues is granted to people by issue permissions.</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <span>Scheme:</span>
                        <?php
                            $permissionScheme = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getMetaDataById($project['permission_scheme_id']);
                        ?>
                        <span><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>"><?php echo $permissionScheme['name'] ?></a></span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <span>Issues:</span>
                        <?php
                            $issueSecurityScheme = null;
                            if ($project['issue_security_scheme_id']) {
                                $issueSecurityScheme = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getMetaDataById($project['issue_security_scheme_id']);
                            }
                        ?>
                        <?php if ($issueSecurityScheme): ?>
                            <span><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>"><?php echo $issueSecurityScheme['name'] ?></a></span>
                        <?php else: ?>
                            <span>None</span>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>