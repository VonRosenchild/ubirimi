<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\YongoProject;

?>
<table width="100%">
    <tr>
        <td id="sectProjectVersions" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Versions</span></td>
    </tr>

    <tr>
        <td>
            <table width="100%" id="contentProjectVersions">
                <tr>
                    <td>
                        <span>For software projects, Ubirimi allows you to track different versions, e.g. 1.0, 2.0. Issues can be assigned to versions.</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <?php
                            $versions = UbirimiContainer::get()['repository']->get(YongoProject::class)->getVersions(array($projectId));
                            if ($versions) {
                                while ($version = $versions->fetch_array(MYSQLI_ASSOC)) {
                                    echo '<div>' . $version['name'] . '</div>';
                                }
                                echo '<div>';
                                echo '<a href="/yongo/administration/project/version/add/' . $projectId . '">Add version</a> ';
                                echo '<a href="/yongo/administration/project/versions/' . $projectId . '">More</a>';
                                echo '</div>';
                            }
                        ?>
                        <div>The project does not have any versions. <a href="/yongo/administration/project/version/add/<?php echo $projectId ?>">Add version</a></div>
                        <span><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">More</a></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>