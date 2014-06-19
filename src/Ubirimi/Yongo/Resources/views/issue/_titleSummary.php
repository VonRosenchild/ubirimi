<table width="100%" class="headerPageBackground">
    <tr>
        <td width="48px">
            <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
        </td>
        <td>
            <div class="headerPageText">
                <a class="linkNoUnderline" href="/yongo/project/<?php echo $projectId ?>"><?php echo $issueProject['name'] ?></a> /
                <?php
                    if (isset($parentIssue)) {
                        echo $parentIssue['project_code'] . '-' . $parentIssue['nr'] . ' <a class="linkNoUnderline" href="/yongo/issue/' . $parentIssue['id'] . '">' . $parentIssue['summary'] . '</a> / ';
                    }
                    echo $issue['project_code'] . '-' . $issue['nr']
                ?>
            </div>

            <div class="issueSummaryTitle"><?php echo htmlentities($issue['summary']) ?></div>
        </td>
    </tr>
</table>