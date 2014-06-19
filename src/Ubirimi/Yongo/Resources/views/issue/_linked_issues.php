<tr>
    <td id="sectLinkedIssues" class="sectionDetail" colspan="2">
        <span class="sectionDetailTitle headerPageText">Issue Links</span>
    </td>
</tr>
<tr>
    <td>
        <div id="contentLinkedIssues">
            <table width="100%" id="content_list" cellspacing="0" cellpadding="3">
                <?php while ($linkedIssue = $linkedIssues->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td width="150px">
                            <?php if ($linkedIssue['view'] == 'parent'): ?>
                                <?php if ($linkedIssue['link_type'] == 'outward'): ?>
                                    <?php echo $linkedIssue['outward_description'] ?>
                                <?php else: ?>
                                    <?php echo $linkedIssue['inward_description'] ?>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if ($linkedIssue['link_type'] == 'outward'): ?>
                                    <?php echo $linkedIssue['inward_description'] ?>
                                <?php else: ?>
                                    <?php echo $linkedIssue['outward_description'] ?>
                                <?php endif ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <img title="<?php echo $linkedIssue['type'] . ' - ' . $linkedIssue['issue_type_description'] ?>" height="16px" src="/yongo/img/issue_type/<?php echo $linkedIssue['issue_type_icon_name'] ?>" />
                            <a href="/yongo/issue/<?php echo $linkedIssue['id'] ?>"><?php echo $issue['project_code'] . '-' . $linkedIssue['nr'] ?></a> <?php echo mb_substr($linkedIssue['summary'], 0, 110) ?>
                        </td>
                        <td align="right" width="40px">
                            <img title="<?php echo $linkedIssue['priority'] . ' - ' . $linkedIssue['issue_priority_description'] ?>" height="16px" src="/yongo/img/issue_priority/<?php echo $linkedIssue['issue_priority_icon_name'] ?>" />
                        </td>
                        <td align="right"width="80px">
                            <?php echo $linkedIssue['status_name'] ?>
                        </td>
                        <td align="right">
                            <img title="Delete Issue Link" id="deleteIssueLink_<?php echo $linkedIssue['issue_link_id'] ?>" src="/img/delete.png" height="16px"/>
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
        </div>
    </td>
</tr>