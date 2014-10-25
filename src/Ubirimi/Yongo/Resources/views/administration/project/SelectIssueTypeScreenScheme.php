<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > ' . $project['name'] . ' > Issue Type Screen Scheme > Select a Different Scheme</div>';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <form name="select_issue_type_screen_scheme" method="post" action="/yongo/administration/project/select-issue-type-screen-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td width="200">Issue Type Screen Scheme</td>
                    <td>
                        <select name="issue_type_screen_scheme" class="select2InputMedium">
                            <?php while ($issueTypeScreenScheme = $issueTypeScreenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($issueTypeScreenScheme['id'] == $project['issue_type_screen_scheme_id']) echo 'selected="selected"' ?> value="<?php echo $issueTypeScreenScheme['id'] ?>"><?php echo $issueTypeScreenScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="associate" class="btn ubirimi-btn">Associate</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/screens/<?php echo $projectId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>