<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\TypeScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php
            $breadCrumb = '<a href="/yongo/administration/issue-type-schemes" class="linkNoUnderline">Issue Type Schemes</a> > ' . $issueTypeScheme['name'];
            Util::renderBreadCrumb($breadCrumb);
        ?>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Options</th>
                    <th align="left">Projects</th>
                </tr>
            </thead>
            <tbody>
            <tr id="table_row_<?php echo $issueTypeScheme['id'] ?>">
                <td>
                    <?php
                        echo '<div>' . $issueTypeScheme['name'] . '</div>';
                        echo '<div class="smallDescription">' . $issueTypeScheme['description'] . '</div>';
                    ?>
                </td>
                <td>
                    <?php
                        $dataIssueTypeScheme = TypeScheme::getDataById($issueTypeScheme['id']);
                        while ($data = $dataIssueTypeScheme->fetch_array(MYSQLI_ASSOC)) {
                            echo '<div>' . $data['name'] . '</div>';
                        }
                    ?>
                </td>
                <td valign="top">
                    <?php
                        $projects = Project::getByIssueTypeScheme($issueTypeScheme['id']);
                        if ($projects) {
                            echo '<ul>';
                            while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>