<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <?php if ($selectedProjectId): ?>
        <tr>
            <td>
                <div style="cursor: text; background-color: #ffffff;"><b>Current Project</b></div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="<?php echo $urlPrefix . $selectedProjectId ?>"><?php echo $selectedProjectMenu['name'] . ' (' . $selectedProjectMenu['code'] . ')' ?></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
    <?php endif ?>

    <?php
        $htmlOtherProjects = '';
        $menuOtherProjectsVisible = false;
        if ($projectsMenu) {
            $htmlOtherProjects .= '<tr>';
                $htmlOtherProjects .= '<td><div style="cursor: text; background-color: #ffffff;"><b>Other Projects</b></div></td>';
            $htmlOtherProjects .= '</tr>';

            $showProjectsCount = 0;
            for ($i = 0; $i < count($projectsMenu); $i++) {
                if ($selectedProjectId != $projectsMenu[$i]['id']) {

                    $menuOtherProjectsVisible = true;
                    $htmlOtherProjects .= '<tr>';
                        $htmlOtherProjects .= '<td>';
                            $htmlOtherProjects .= '<div>';
                                $htmlOtherProjects .= '<a class="linkSubMenu" href="' . $urlPrefix . $projectsMenu[$i]['id'] . '">' . $projectsMenu[$i]['name'] . ' (' . $projectsMenu[$i]['code'] . ')' . '</a>';
                            $htmlOtherProjects .= '</div>';
                        $htmlOtherProjects .= '</td>';
                    $htmlOtherProjects .= '</tr>';
                    $showProjectsCount++;
                    if ($showProjectsCount >= 10) {
                        break;
                    }
                }
            }
            $htmlOtherProjects .= '<tr>';
                $htmlOtherProjects .= '<td>';
                    $htmlOtherProjects .= '<span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>';
                $htmlOtherProjects .= '</td>';
            $htmlOtherProjects .= '</tr>';
        }

        if ($menuOtherProjectsVisible) {
            echo $htmlOtherProjects;
        }
    ?>

    <tr>
        <td>
            <div><a class="linkSubMenu" href="<?php echo $urlPrefix ?>all">View All Projects</a></div>
        </td>
    </tr>
    <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/yongo/administration/project/add">Create Project</a>
                </div>
            </td>
        </tr>
    <?php endif ?>
</table>