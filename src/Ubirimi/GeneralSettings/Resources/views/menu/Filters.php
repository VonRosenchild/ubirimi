<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <tr>
        <td>
            <div>
                <a class="linkSubMenu" href="/yongo/issue/search?project=<?php if (count($projectsForBrowsing)) echo implode('|', $projectsForBrowsing); else echo '-1'; ?>&resolution=-2&assignee=<?php echo $loggedInUserId ?>">My Open Issues</a>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <a class="linkSubMenu" href="/yongo/issue/search?project=<?php if (count($projectsForBrowsing)) echo implode('|', $projectsForBrowsing); else echo '-1'; ?>&reporter=<?php echo $loggedInUserId ?>">Reported By Me</a>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
        </td>
    </tr>
    <?php if ($customFilters): ?>
        <?php while ($customFilters && $filter = $customFilters->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td>
                    <div>
                        <a class="linksubmenu" href="/yongo/issue/search?filter=<?php echo $filter['id'] ?>&<?php echo $filter['definition'] ?>"><?php echo $filter['name'] ?></a>
                    </div>
                </td>
            </tr>
        <?php endwhile ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
    <?php endif ?>
    <tr>
        <td>
            <div><a class="linkSubMenu" href="/yongo/filter/all">Manage Filters</a></div>
        </td>
    </tr>
</table>