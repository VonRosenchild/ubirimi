<?php
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Screen Schemes') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Screen Schemes') ?>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/screens">Screens</a></li>
            <li class="active"><a href="/yongo/administration/screens/schemes">Screen Schemes</a></li>
            <li><a href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/screen/add-scheme" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Screen Scheme</a></td>
                <td><a id="btnEditScreenSchemeMetadata" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnConfigureScreenScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                <td><a id="btnCopyScreenScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                <td><a id="btnDeleteScreenScheme" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <div class="infoBox">Screen Schemes allow you to choose what screens are shown for each issue operation. Screen Schemes are mapped to issue types using <a href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a>, which can be associated with one or more projects. </div>
        <?php if ($screensSchemes): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Issue Type Screen Schemes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($scheme = $screensSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $scheme['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>" /></td>
                            <td>
                                <a href="/yongo/administration/screen/configure-scheme/<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></a>
                            </td>
                            <td width="500px">
                                <?php
                                $issueTypeScreenSchemes = TypeScreenScheme::getByScreenSchemeId($scheme['id']);
                                if ($issueTypeScreenSchemes) {

                                    echo '<ul>';
                                    while ($issueTypeScreenScheme = $issueTypeScreenSchemes->fetch_array(MYSQLI_ASSOC)) {

                                        echo '<li><a href="/yongo/administration/screen/configure-scheme-issue-type/' . $issueTypeScreenScheme['id'] . '">' . $issueTypeScreenScheme['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                    echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="0">';
                                } else {
                                    echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="1">';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no screen schemes defined</div>
        <?php endif ?>
    </div>
        <div class="ubirimiModalDialog" id="modalDeleteScreenScheme"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php endif ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>