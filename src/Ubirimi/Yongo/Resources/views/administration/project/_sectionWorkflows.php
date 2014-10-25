<?php

?>
    <table width="100%">
    <tr>
        <td id="sectWorkflows" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Workflows</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentWorkflows">
                <tr>
                    <td>
                        <span>Issues can follow processes that mirror your team's practices. A workflow defines the sequence of steps that an issue will follow, e.g. "In Progress", "Resolved".</span>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="330">
                        <div>Workflow Scheme</div>
                        <div><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>"><?php echo $workflowScheme['name'] ?></a></div>
                        <div>Workflows</div>
                        <?php while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><a href="/yongo/administration/workflow/view-as-text/<?php echo $workflow['id'] ?>"><?php echo $workflow['name'] ?></a></div>
                        <?php endwhile ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>