<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeLevelId = $_GET['id'];
    $issueSecuritySchemeLevel = IssueSecurityScheme::getLevelById($issueSecuritySchemeLevelId);
    $issueSecuritySchemeId = $issueSecuritySchemeLevel['issue_security_scheme_id'];
    $allLevels = IssueSecurityScheme::getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);

    $issues = Issue::getByParameters(array('client_id' => $clientId, 'security_scheme_level' => $issueSecuritySchemeLevelId));

    if ($issues) {
        echo '<div>There are issues currently set with this Issue Security Level. Confirm what new level should be set for these issues.</div>';
        echo '<div>Issues with this level of security: ' . $issues->num_rows . '</div>';
        echo '<div>';
            echo '<span>Swap security of issues to security level: </span>';
            echo '<select class="inputTextCombo" name="new_level" id="new_security_level">';
                echo '<option value="-1">None</option>';
                while ($allLevels && $level = $allLevels->fetch_array(MYSQLI_ASSOC)) {
                    if ($level['id'] != $issueSecuritySchemeLevelId)
                        echo '<option value="' . $level['id'] . '">' . $level['name'] . '</option>';
                }
            echo '</select>';
        echo '</div>';
    } else {
        echo 'Are you sure you want to delete this issue security level?';
    }