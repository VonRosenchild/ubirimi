<?php

?>
<table width="100%">
    <tr>
        <td id="sectIssueTypes" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Issue Types</span></td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="contentIssueTypes">
                <tr>
                    <td>
                        <span>Keep track of different types of issues, such as bugs or tasks. Each issue type can be configured differently.</span>
                    </td>
                </tr>

                <tr>
                    <td valign="top" width="330">
                        <div>Scheme:</div>
                        <div>
                            <?php
                                echo '<a href="/yongo/administration/project/issue-types/' . $project['id'] . '">' . $issueTypeScheme['name'] . '</a>';
                                while ($issueType = $issueTypeSchemeData->fetch_array(MYSQLI_ASSOC)) {
                                    echo '<div>' . $issueType['name'] . '</div>';
                                }
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>