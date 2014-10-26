<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

?>
<table width="100%">
    <tr>
        <td id="sectScreens" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Screens</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentScreens">
                <tr>
                    <td><span>Screens allow you to arrange the fields to be displayed for an issue. Different screens can be used when an issue is created, viewed, edited, or transitioned through a workflow.</span></td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <?php
                            $issueTypeScreenScheme = UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class)->getMetaDataById($project['issue_type_screen_scheme_id']);
                            $screenSchemes = UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class)->getScreenSchemes($project['issue_type_screen_scheme_id']);
                        ?>
                        <span><a href="/yongo/administration/project/screens/<?php echo $project['issue_type_screen_scheme_id'] ?>"><?php echo $issueTypeScreenScheme['name'] ?></a></span>
                        <?php while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><a href="/yongo/administration/screen/configure-scheme/<?php echo $screenScheme['id'] ?>?source=project&project_id=<?php echo $project['id'] ?>"><?php echo $screenScheme['name'] ?></a></div>
                        <?php endwhile ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>