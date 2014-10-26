<?php use Ubirimi\SystemProduct; ?>

<div class="headerPageBackground">
    <table width="100%">
        <tr>
            <td width="48px" valign="top">
                <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
            </td>
            <td>
                <div class="headerPageText" style="white-space: normal">
                    <?php if (SystemProduct::SYS_PRODUCT_HELP_DESK == $session->get('selected_product_id')): ?>
                        <a class="linkNoUnderline" href="/helpdesk/customer-portal/project/<?php echo $projectId ?>"><?php echo $issueProject['name'] ?></a> /
                    <?php else: ?>
                        <a class="linkNoUnderline" href="/yongo/project/<?php echo $projectId ?>"><?php echo $issueProject['name'] ?></a> /
                    <?php endif ?>
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
</div>