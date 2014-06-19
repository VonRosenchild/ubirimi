<?php

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Associate Issue Security</div>
                </td>
            </tr>
        </table>

        <form name="associate" method="post" action="/yongo/administration/project/associate-issue-security-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td>Step 1 of 2: Select the scheme you wish to associate.</td>
                </tr>
                <tr>
                    <td>
                        <select class="inputTextCombo" name="scheme">
                            <?php while ($issueSecurityScheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $issueSecurityScheme['id'] ?>"><?php echo $issueSecurityScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="next" class="btn ubirimi-btn">Next</button>
                        <button type="submit" name="cancel" class="btn ubirimi-btn">Cancel</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
