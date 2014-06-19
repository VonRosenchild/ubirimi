<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <tr>
        <td>
            <div>
                <a class="linkSubMenu" href="/yongo/issue/search">Search for Issues</a>
            </div>
        </td>
    </tr>
    <?php if ($hasCreateIssuePermission): ?>
        <tr id="btnCreateIssueMenu">
            <td style="width: 100%;">
                <div style="width: 100%;"><a class="linkSubMenu" href="#">Create Issue</a></div>
            </td>
        </tr>
    <?php endif ?>
    <?php if (isset($recentIssues)): ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td>
                <div style="cursor: text; background-color: #ffffff;"><b>Recent Issues</b></div>
            </td>
        </tr>
        <?php for ($i = 0; $i < count($recentIssues); $i++): ?>
            <tr>
                <td>
                    <div>
                        <a class="linkSubMenu" href="<?php echo $recentIssues[$i]['link'] ?>"><?php echo $recentIssues[$i]['project_code'] . '-' . $recentIssues[$i]['nr'] . ' ' . mb_substr($recentIssues[$i]['summary'], 0, 17) ?></a>
                    </div>
                </td>
            </tr>
        <?php endfor ?>
    <?php endif ?>
</table>