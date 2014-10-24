<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\YongoProject;

?>

<table width="100%">
    <tr>
        <td id="sectProjectComponents" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Components</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentProjectComponents">
                <tr>
                    <td>
                        <span>Projects can be broken down into components, e.g. "Database", "User Interface". Issues can then be categorised against different components.</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <?php
                            $components = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents(array($projectId));
                            if ($components) {
                                while ($component = $components->fetch_array(MYSQLI_ASSOC)) {
                                    echo '<div>' . $component['name'] . '</div>';
                                }
                            }
                        ?>
                        <?php if (!$components): ?>
                            <div>
                                <span>This project does not use any components.</span>
                                <span><a href="/yongo/administration/project/component/add/<?php echo $projectId ?>">Add component</a></span>
                            </div>
                        <?php else: ?>
                            <span><a href="/yongo/administration/project/component/add/<?php echo $projectId ?>">Add component</a></span>
                        <?php endif ?>
                        <span><a href="/yongo/administration/project/components/<?php echo $projectId ?>">More</a></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>